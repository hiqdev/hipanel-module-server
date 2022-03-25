<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\server\grid\ServerGridLegend;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\modules\server\widgets\PowerManagementDropdown;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\AjaxModalWithTemplatedButton;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use hiqdev\higrid\representations\RepresentationCollection;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var OsimageSearch
 * @var yii\web\View $this
 * @var IndexPageUiOptions $uiModel
 * @var RepresentationCollection $representationCollection
 * @var bool $orderIsAllowed
 */
$this->title = Yii::t('hipanel:server', 'Servers');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;
$orderIsAllowed = $this->context->module->orderIsAllowed;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

    <?php if (Yii::$app->user->can('support')) : ?>
        <?php $page->beginContent('legend') ?>
            <?= GridLegend::widget(['legendItem' => new ServerGridLegend($model)]) ?>
        <?php $page->endContent() ?>
    <?php endif ?>

    <?php $page->beginContent('main-actions') ?>
        <?php if (Yii::$app->user->can('server.create')) : ?>
            <?= Html::a(Yii::t('hipanel:server', 'Create server'), ['@server/create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?php endif ?>
        <?php if (Yii::$app->user->can('server.pay') && $orderIsAllowed) : ?>
            <?= Html::a(Yii::t('hipanel:server:order', 'Buy server'), ['/server/order/index'], ['class' => 'btn btn-sm btn-primary']) ?>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => array_filter([
                'name', 'tariff', 'ip',
                Yii::$app->user->can('support') ? 'client' : null,
            ]),
        ]) ?>
    <?php $page->endContent() ?>
    <?php $page->beginContent('representation-actions') ?>
        <?= $page->renderRepresentations($representationCollection) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if ($uiModel->representation === 'hardware'
            && Yii::getAlias('@part', false)
            && Yii::$app->user->can('part.read')
        ): ?>
            <?php
            $partsDetailsLink = Json::htmlEncode(Url::to(\hipanel\helpers\Url::toSearch('part')));
            echo Html::button(Yii::t('hipanel:server', 'View parts'), [
                    'class' => 'btn btn-sm btn-default',
                    'onClick' => new JsExpression(<<<JS
                        const selection = jQuery('div[role="grid"]').yiiGridView('getSelectedRows');
                        window.location.href = $partsDetailsLink + '?' + jQuery.param({PartSearch: {dst_id_in: selection}});
JS
            ), ]) ?>
        <?php endif ?>
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
            <?php if (
                    Yii::$app->user->can('server.update')
                ||  Yii::$app->user->can('server.set-note')
                ||  Yii::$app->user->can('server.enable-block')
                ||  Yii::$app->user->can('server.disable-block')
                || Yii::$app->user->can('manage')
            ) : ?>
                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('hipanel', 'Basic actions') ?>
                    <span class="caret"></span>
                </button>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'pull-right'],
                    'items' => array_filter([
                        [
                            'label' => '<i class="fa fa-plug"></i> ' . Yii::t('hipanel:server', 'Assign hubs'),
                            'url' => '#',
                            'linkOptions' => ['data-action' => 'assign-hubs'],
                            'visible' => Yii::$app->user->can('server.update'),
                        ],
                        Yii::$app->user->can('server.update') ? AjaxModalWithTemplatedButton::widget([
                            'ajaxModalOptions' => [
                                'id' => 'bulk-set-type-modal',
                                'bulkPage' => true,
                                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Change type'), ['class' => 'modal-title']),
                                'scenario' => 'set-type',
                                'toggleButton' => [
                                    'tag' => 'a',
                                    'label' => '<i class="fa fa-pencil"></i> ' . Yii::t('hipanel:server', 'Change type'),
                                ],
                            ],
                            'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                        ]) : null,
                        [
                            'label' => '<i class="fa fa-pencil"></i> ' . Yii::t('hipanel:server', 'Set one type to many servers'),
                            'url' => '#',
                            'linkOptions' => ['data-action' => 'set-one-type'],
                            'visible' => Yii::$app->user->can('server.update'),
                        ],
                        Yii::$app->user->can('server.update') ? [
                            'label' => '<i class="fa fa-cog"></i> ' . Yii::t('hipanel:server', 'Set units'),
                            'url' => '#',
                            'linkOptions' => ['data-action' => 'set-units'],
                            'visible' => Yii::$app->user->can('server.update'),
                        ] : null,
                        [
                            'label' => '<i class="fa fa-cog"></i> ' . Yii::t('hipanel:server', 'Set Rack No.'),
                            'url' => '#',
                            'linkOptions' => ['data-action' => 'set-rack-no'],
                            'visible' => Yii::$app->user->can('server.update'),
                        ],
                        Yii::$app->user->can('consumption.delete') ? AjaxModalWithTemplatedButton::widget([
                            'ajaxModalOptions' => [
                                'id' => 'clear-resources-modal',
                                'bulkPage' => true,
                                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Clear resources'), ['class' => 'modal-title']),
                                'scenario' => 'clear-resources-modal',
                                'toggleButton' => [
                                    'tag' => 'a',
                                    'label' => '<i class="fa fa-history"></i> ' . Yii::t('hipanel:server', 'Clear resources'),
                                ],
                            ],
                            'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                        ]) : null,
                        Yii::$app->user->can('consumption.delete') ? AjaxModalWithTemplatedButton::widget([
                            'ajaxModalOptions' => [
                                'id' => 'flush-modal',
                                'bulkPage' => true,
                                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Flush switch graphs'), ['class' => 'modal-title']),
                                'scenario' => 'flush-switch-graphs-modal',
                                'toggleButton' => [
                                    'tag' => 'a',
                                    'label' => '<i class="fa fa-history"></i> ' . Yii::t('hipanel:server', 'Flush switch graphs'),
                                ],
                            ],
                            'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                        ]) : null,
                        Yii::$app->user->can('server.disable-block') ? AjaxModalWithTemplatedButton::widget([
                            'ajaxModalOptions' => [
                                'bulkPage' => true,
                                'id' => 'bulk-enable-block-modal',
                                'scenario' => 'bulk-enable-block-modal',
                                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Block servers'), ['class' => 'modal-title label-warning']),
                                'headerOptions' => ['class' => 'label-warning'],
                                'toggleButton' => [
                                    'tag' => 'a',
                                    'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel', 'Enable block'),
                                ],
                            ],
                            'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                        ]) : null,
                        Yii::$app->user->can('server.enable-block') ? AjaxModalWithTemplatedButton::widget([
                            'ajaxModalOptions' => [
                                'bulkPage' => true,
                                'id' => 'bulk-disable-block-modal',
                                'scenario' => 'bulk-disable-block-modal',
                                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Unblock servers'), ['class' => 'modal-title']),
                                'headerOptions' => ['class' => 'label-warning'],
                                'toggleButton' => [
                                    'tag' => 'a',
                                    'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel', 'Disable block'),
                                ],
                            ],
                            'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                        ]) : null,
                        Yii::$app->user->can('server.set-note') ? AjaxModalWithTemplatedButton::widget([
                            'ajaxModalOptions' => [
                                'bulkPage' => true,
                                'id' => 'servers-set-note',
                                'scenario' => Yii::$app->user->can('support') ? 'set-label' : 'set-note',
                                'size' => Modal::SIZE_LARGE,
                                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Set notes'), ['class' => 'modal-title']),
                                'toggleButton' => [
                                    'tag' => 'a',
                                    'label' => '<i class="fa fa-pencil"></i> ' . Yii::t('hipanel:server', 'Set notes'),
                                ],
                            ],
                            'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                        ]) : null,
                        Yii::$app->user->can('server.delete') ? AjaxModalWithTemplatedButton::widget([
                            'ajaxModalOptions' => [
                                'id' => 'bulk-delete-modal',
                                'bulkPage' => true,
                                'scenario' => 'bulk-delete-modal',
                                'header' => Html::tag('h4', Yii::t('hipanel', 'Delete'), ['class' => 'modal-title label-danger']),
                                'headerOptions' => ['class' => 'label-danger'],
                                'toggleButton' => [
                                    'tag' => 'a',
                                    'label' => '<i class="fa fa-trash"></i> ' . Yii::t('hipanel', 'Delete'),
                                ],
                            ],
                            'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                        ]) : null,
                    ]),
                ]) ?>
            <?php endif ?>
        </div>
        <?= PowerManagementDropdown::widget(['model' => $model]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm(); ?>
            <?= ServerGridView::widget([
                'dataProvider' => $dataProvider,
                'boxed' => false,
                'colorize' => true,
                'filterModel' => $model,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
                'rowOptions' => function ($model) {
                    return GridLegend::create(new ServerGridLegend($model))->gridRowOptions();
                },
            ]) ?>
        <?php $page->endBulkForm(); ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
<?php Pjax::end() ?>
