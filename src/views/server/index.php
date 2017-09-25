<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\server\grid\ServerGridLegend;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * @var OsimageSearch $osimages
 * @var yii\web\View $this
 * @var IndexPageUiOptions $uiModel
 * @var \hiqdev\higrid\representations\RepresentationCollection $representationCollection
 */

$this->title = Yii::t('hipanel:server', 'Servers');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

    <?php if (Yii::$app->user->can('support')) : ?>
        <?php $page->beginContent('legend') ?>
            <?= GridLegend::widget(['legendItem' => new ServerGridLegend($model)]) ?>
        <?php $page->endContent() ?>
    <?php endif ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'name', 'id',
                'client', 'tariff', 'ip',
                'state', 'status_time', 'expires',
            ],
        ]) ?>
    <?php $page->endContent() ?>
    <?php $page->beginContent('representation-actions') ?>
        <?= $page->renderRepresentations($representationCollection) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if (Yii::$app->user->can('server.sell')): ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-sale-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Sell servers'), ['class' => 'modal-title']),
                'scenario' => 'bulk-sale',
                'size' => Modal::SIZE_LARGE,
                'toggleButton' => ['label' => Yii::t('hipanel:server', 'Sell'), 'class' => 'btn btn-sm btn-default'],
            ]) ?>
        <?php endif ?>
        <div class="dropdown" style="display: inline-block">
            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= Yii::t('hipanel', 'Basic actions') ?>
                <span class="caret"></span>
            </button>
            <?= Dropdown::widget([
                'encodeLabels' => false,
                'options' => ['class' => 'pull-right'],
                'items' => array_filter([
                    Yii::$app->user->can('manage') ? [
                        'label' => '<i class="fa fa-pencil"></i> ' . Yii::t('hipanel:server', 'Change type'),
                        'url' => '#bulk-set-type-modal',
                        'linkOptions' => ['data-toggle' => 'modal']
                    ] : null,
                    Yii::$app->user->can('manage') ? [
                        'label' => '<i class="fa fa-history"></i> ' . Yii::t('hipanel:server', 'Clear resources'),
                        'url' => '#clear-resources-modal',
                        'linkOptions' => ['data-toggle' => 'modal']
                    ] : null,
                    Yii::$app->user->can('manage') ? [
                        'label' => '<i class="fa fa-history"></i> ' . Yii::t('hipanel:server', 'Flush switch graphs'),
                        'url' => '#flush-modal',
                        'linkOptions' => ['data-toggle' => 'modal']
                    ] : null,
                    Yii::$app->user->can('support') ? [
                        'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel', 'Enable block'),
                        'url' => '#bulk-enable-block-modal',
                        'linkOptions' => ['data-toggle' => 'modal']
                    ] : null,
                    Yii::$app->user->can('support') ? [
                        'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel', 'Disable block'),
                        'url' => '#bulk-disable-block-modal',
                        'linkOptions' => ['data-toggle' => 'modal']
                    ] : null,
                    [
                        'label' => '<i class="fa fa-pencil"></i> ' . Yii::t('hipanel:server', 'Set notes'),
                        'url' => '#bulk-set-notes-modal',
                        'linkOptions' => ['data-toggle' => 'modal']
                    ],
                    Yii::$app->user->can('support') ? [
                        'label' => '<i class="fa fa-trash"></i> ' . Yii::t('hipanel', 'Delete'),
                        'url' => '#bulk-delete-modal',
                        'linkOptions' => ['data-toggle' => 'modal']
                    ] : null,
                ]),
            ]); ?>
        </div>
        <?php if (Yii::$app->user->can('support')) : ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-enable-block-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Block servers'), ['class' => 'modal-title label-warning']),
                'headerOptions' => ['class' => 'label-warning'],
                'scenario' => 'bulk-enable-block-modal',
                'handleSubmit' => false,
                'toggleButton' => false,
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-disable-block-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Unblock servers'), ['class' => 'modal-title']),
                'headerOptions' => ['class' => 'label-warning'],
                'scenario' => 'bulk-disable-block-modal',
                'handleSubmit' => false,
                'toggleButton' => false,
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-delete-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel', 'Delete'), ['class' => 'modal-title label-danger']),
                'headerOptions' => ['class' => 'label-danger'],
                'scenario' => 'bulk-delete-modal',
                'handleSubmit' => false,
                'toggleButton' => false,
            ]) ?>
        <?php endif ?>
        <?php if (Yii::$app->user->can('manage')) : ?>
            <?= AjaxModal::widget([
                'id' => 'bulk-set-type-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Change type'), ['class' => 'modal-title']),
                'scenario' => 'set-type',
                'toggleButton' => false,
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'clear-resources-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Clear resources'), ['class' => 'modal-title']),
                'scenario' => 'clear-resources-modal',
                'toggleButton' => false,
            ]) ?>
            <?= AjaxModal::widget([
                'id' => 'flush-modal',
                'bulkPage' => true,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Flush switch graphs'), ['class' => 'modal-title']),
                'scenario' => 'flush-switch-graphs-modal',
                'toggleButton' => false,
            ]) ?>
        <?php endif ?>
        <?= AjaxModal::widget([
            'id' => 'bulk-set-notes-modal',
            'bulkPage' => true,
            'header' => Html::tag('h4', Yii::t('hipanel:server', 'Set notes'), ['class' => 'modal-title']),
            'scenario' => Yii::$app->user->can('support') ? 'set-label' : 'set-note',
            'toggleButton' => false,
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm(); ?>
            <?= ServerGridView::widget([
                'dataProvider' => $dataProvider,
                'boxed' => false,
                'colorize' => true,
                'filterModel' => $model,
                'osImages' => $osimages,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
                'rowOptions' => function ($model) {
                    return GridLegend::create(new ServerGridLegend($model))->gridRowOptions();
                },
            ]) ?>
        <?php $page->endBulkForm(); ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
<?php Pjax::end() ?>
