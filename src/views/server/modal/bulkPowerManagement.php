<?php

use hipanel\modules\server\forms\PowerManagementForm;
use hipanel\modules\server\models\Server;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;

/** @var PowerManagementForm $model */
/** @var PowerManagementForm[] $models */
/** @var string $scenario */

$successText = Json::encode(Yii::t('hipanel:server', 'All requests completed successfully'));
$areThereAnyObjects = Json::encode(count($model->getIncluded()) !== 0);

$this->registerJs(<<<"JS"
$(() => {
  const reason = document.querySelector('.modal.in textarea');
  const btn = document.querySelector('.modal.in .btn-success');
  const reasonHandler = () => {
    if (reason.value.length > 1 && {$areThereAnyObjects}) {
      btn.removeAttribute('disabled');
    } else {
      btn.setAttribute('disabled', 'disabled');
    }
  };
  reason.addEventListener('keyup', reasonHandler);
  $("#bulk-power-management-form").submit(e => {
    e.preventDefault();
    e.stopPropagation();
    const form = $(e.currentTarget), button = form.find('button');
    if (button.attr('disabled')) {
      return;
    }
    const checkRequestStatus = ids => {
      $.ajax({
        url: '/hosting/request/search',
        data: {id_in: ids}
      }).done(res => {
        ids.forEach(id => {
          const found = res.filter(row => parseInt(row.object_id) === parseInt(id));
          if (found.length === 0) {
            const rowItem = $('#' + id);
            rowItem.find('td:last-child').html('<span class="fa fa-fw fa-check text-success" title="Done"></span>');
            rowItem.removeClass('in_progress');
          }
        });
      });
    };
    $.ajax({
      type: form.attr("method"),
      url: form.attr("action"),
      data: form.serialize(),
      beforeSend: () => {
        button.button('loading');
      }
    }).done(resp => {
      hipanel.notify.success(resp.text);
      reason.removeEventListener('keyup', reasonHandler);
      button.button('reset');
      $('.check-step').show();
      $('.form-step').hide();
      let timerId = setInterval(() => {
        const ids = [];
        const rowsInProgress = form.parents('.form-step').siblings('.check-step').find('.in_progress');
        rowsInProgress.each((idx, row) => {
          ids.push(row.id);
        });
        if (ids.length === 0) { 
          clearInterval(timerId);
          hipanel.notify.success({$successText});
        } else {
          checkRequestStatus(ids);
        }
      }, 5000);
      form.parents('.modal').one('hide.bs.modal', () => {
        clearInterval(timerId); 
      });
    }).fail(err => {
      hipanel.notify.error(err.text)
    });
  });
});
JS
);

?>
<div class="check-step" style="display: none;">
    <?= GridView::widget([
        'showFooter' => false,
        'layout' => '{items}',
        'options' => ['class' => 'grid-view', 'style' => ''],
        'tableOptions' => ['class' => 'table table-condensed'],
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->getIncluded(),
            'pagination' => false,
            'modelClass' => Server::class,
        ]),
        'rowOptions' => static fn(Server $server, $key): array => [
            'id' => $server->id,
            'class' => 'in_progress',
        ],
        'columns' => [
            [
                'label' => Yii::t('hipanel:server', 'Server'),
                'attribute' => 'name',
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-bold'],
                'value' => static fn(Server $server): string => Html::a($server->name, ['@server/view', 'id' => $server->id]),
            ],
            [
                'label' => Yii::t('hipanel:server', 'Execution status'),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'value' => static function (Server $server) {
                    return Html::tag('i', null, ['class' => 'fa fa-spinner fa-pulse fa-fw text-muted']);
                },
            ],
        ],
    ]) ?>
</div>

<div class="form-step">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('hipanel:server', 'Total selected'),
                'contentOptions' => ['class' => 'text-center'],
                'value' => static function (PowerManagementForm $form) {
                    return count($form->getServers());
                },
            ],
            [
                'label' => Yii::t('hipanel:server', 'Will be applied to servers'),
                'contentOptions' => ['class' => 'bg-success text-center'],
                'value' => static function (PowerManagementForm $form) {
                    return count($form->getIncluded());
                },
            ],
            [
                'label' => Yii::t('hipanel:server', 'Will not be applied'),
                'format' => 'raw',
                'contentOptions' => ['class' => 'bg-danger text-center'],
                'value' => static fn(PowerManagementForm $form) => ArraySpoiler::widget([
                    'data' => $form->getNotIncluded(),
                    'id' => mt_rand(),
                    'visibleCount' => 0,
                    'button' => [
                        'label' => count($form->getNotIncluded()),
                        'class' => 'clickable',
                        'popoverOptions' => [
                            'placement' => 'right',
                            'html' => true,
                            'title' => Yii::t('hipanel:server', 'Filtered servers'),
                            'template' => '
                            <div class="popover" role="tooltip">
                                <div class="arrow"></div>
                                <h3 class="popover-title"></h3>
                                <div class="popover-content" style="height: 25rem; width: 20rem; overflow-x: scroll;"></div>
                            </div>
                        ',
                        ],
                    ],
                    'formatter' => static fn(Server $server) => Html::a(
                        '<i class="fa fa-server fa-fw"></i>&nbsp;' . $server->name,
                        ['view', 'id' => $server->id],
                        ['target' => '_blank']
                    ),
                    'delimiter' => '<br />',
                ]),
            ],
        ],
    ]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'bulk-power-management-form',
        'action' => ["@server/{$model->scenario}"],
        'validateOnChange' => true,
    ]); ?>

    <?php foreach ($model->getIncluded() as $server) : ?>
        <?= Html::hiddenInput('server_ids[]', $server->id) ?>
    <?php endforeach ?>

    <?= $form->field($model, 'reason')->textarea(['rows' => 3]) ?>

    <?= Html::submitButton(Yii::t('hipanel:server', 'Execute action'), [
        'class' => 'btn btn-success btn-block',
        'disabled' => true,
        'data-loading-text' => Yii::t('hipanel:server', 'Sending requests...'),
    ]) ?>

    <?php ActiveForm::end() ?>
</div>
