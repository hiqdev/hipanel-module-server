<?php

use hipanel\modules\server\forms\PowerManagementForm;
use hipanel\modules\server\models\Server;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var PowerManagementForm $model */
/** @var PowerManagementForm[] $models */
/** @var string $scenario */

?>
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
]); ?>

<?php foreach ($model->getIncluded() as $server) : ?>
    <?= Html::hiddenInput('server_ids[]', $server->id) ?>
<?php endforeach ?>

<?= $form->field($model, 'reason')->textarea(['rows' => 3]) ?>

<?= Html::submitButton(Yii::t('hipanel:server', 'Execute action'), ['class' => 'btn btn-success btn-block', 'disabled' => count($model->getIncluded()) === 0]) ?>

<?php ActiveForm::end() ?>
