<?php

use hipanel\base\View;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\widgets\ActionBox;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\BulkButtons;
use hipanel\widgets\LinkSorter;
use hipanel\widgets\Pjax;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * @var OsimageSearch $osimages
 */

/**
 * @var View $this
 */
$this->title = Yii::t('hipanel/server', 'Servers');
$this->subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->breadcrumbs->setItems([
    $this->title
]);

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true]));
?>

<?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
<?php $box->beginActions() ?>
    <?= $box->renderSearchButton() ?>
<?= $box->renderSorter([
    'attributes' => [
        'name',
        'id',
        'client',
        'tariff',
        'ip',
        'state',
        'status_time',
        'expires',
    ],
]) ?>
<?= $box->renderPerPage() ?>
<?php $box->endActions() ?>
<?php $box->beginBulkActions() ?>
    <div class="dropdown" style="display: inline-block">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= Yii::t('hipanel', 'Basic actions') ?>
            <span class="caret"></span>
        </button>
        <?= Dropdown::widget([
            'encodeLabels' => false,
            'items' => [
                ['label' => Yii::t('hipanel/domain', 'Block servers'), 'url' => '#bulk-server-block-modal', 'linkOptions' => ['data-toggle' => 'modal']],
                ['label' => Yii::t('hipanel/domain', 'Unblock servers'), 'url' => '#bulk-server-unblock-modal', 'linkOptions' => ['data-toggle' => 'modal']],
                ['label' => Yii::t('hipanel', 'Delete'), 'url' => '#bulk-server-delete-modal', 'linkOptions' => ['data-toggle' => 'modal']],
            ]
        ]); ?>
    </div>
<?= AjaxModal::widget([
    'id' => 'bulk-server-block-modal',
    'bulkPage' => true,
    'header' => Html::tag('h4', Yii::t('hipanel/server', 'Block servers'), ['class' => 'modal-title']),
    'scenario' => 'enable-block',
    'actionUrl' => ['bulk-enable-block-modal'],
    'toggleButton' => false,
]) ?>
<?= AjaxModal::widget([
    'id' => 'bulk-server-unblock-modal',
    'bulkPage' => true,
    'header' => Html::tag('h4', Yii::t('hipanel/server', 'Unblock servers'), ['class' => 'modal-title']),
    'scenario' => 'disable-block',
    'actionUrl' => ['bulk-disable-block-modal'],
    'toggleButton' => false,
]) ?>
<?= AjaxModal::widget([
    'id' => 'bulk-server-delete-modal',
    'bulkPage' => true,
    'header' => Html::tag('h4', Yii::t('hipanel', 'Delete'), ['class' => 'modal-title']),
    'scenario' => 'delete',
    'actionUrl' => ['bulk-delete-modal'],
    'toggleButton' => false,
]) ?>
<?php $box->endBulkActions() ?>
<?= $box->renderSearchForm(compact('states')) ?>
<?php $box->end() ?>

<?php
$box->beginBulkForm();
print ServerGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'osImages' => $osimages,
    'columns' => [
        'checkbox',
        'server',
        'client_id',
        'seller_id',
        'ips',
        'state',
        'expires',
        'tariff_and_discount',
        'actions',
    ]
]);
$box->endBulkForm();
Pjax::end();
