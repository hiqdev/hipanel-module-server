<?php
/**
 * @var \yii\web\View $this
 * @var \hipanel\modules\server\models\HubSearch $model
 * @var \hipanel\models\IndexPageUiOptions $uiModel
 * @var \hipanel\modules\server\grid\HubRepresentations $representationCollection
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $types
 */
use hipanel\modules\server\grid\HubGridLegend;
use hipanel\modules\server\grid\HubGridView;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('hipanel:server', 'Switches');
$this->params['breadcrumbs'][] = $this->title;
$subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->registerCss('
    .sale-flex-cnt {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        flex-wrap: wrap;
    }
');

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->setSearchFormData(['types' => $types]) ?>

        <?php $page->beginContent('main-actions') ?>
            <?php if (Yii::$app->user->can('hub.create')) : ?>
                <?= Html::a(Yii::t('hipanel:server:hub', 'Create switch'), ['@hub/create'], ['class' => 'btn btn-sm btn-success']) ?>
            <?php endif; ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('legend') ?>
            <?= GridLegend::widget(['legendItem' => new HubGridLegend($model)]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('sorter-actions') ?>
            <?= $page->renderSorter(['attributes' => ['id']]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('representation-actions') ?>
            <?= $page->renderRepresentations($representationCollection) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('bulk-actions') ?>
            <?php if (Yii::$app->user->can('hub.sell')) : ?>
                <?= AjaxModal::widget([
                    'bulkPage' => true,
                    'id' => 'hubs-sell',
                    'scenario' => 'sell',
                    'actionUrl' => ['sell'],
                    'handleSubmit' => Url::toRoute('sell'),
                    'size' => Modal::SIZE_LARGE,
                    'header' => Html::tag('h4', Yii::t('hipanel:server:hub', 'Sell switches'), ['class' => 'modal-title']),
                    'toggleButton' => ['label' => Yii::t('hipanel:server:hub', 'Sell switches'), 'class' => 'btn btn-default btn-sm'],
                ]) ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('hub.update')) : ?>
                <?= $page->renderBulkButton('update', '<i class="fa fa-pencil"></i>&nbsp;&nbsp;' . Yii::t('hipanel', 'Update'))?>
                <?= $page->renderBulkButton('assign-switches', '<i class="fa fa-plug"></i>&nbsp;&nbsp;' . Yii::t('hipanel:server:hub', 'Switches')) ?>
            <?php endif ?>
            <div class="dropdown">
                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('hipanel:server:hub', 'Bulk actions') ?>
                    <span class="caret"></span>
                </button>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'pull-right'],
                    'items' => array_filter([
                        [
                            'label' => '<i class="fa fa-cog"></i> ' . Yii::t('hipanel:server', 'Set Rack No.'),
                            'url' => '#',
                            'linkOptions' => ['data-action' => 'set-rack-no'],
                            'visible' => Yii::$app->user->can('hub.update'),
                        ],
                    ]),
                ]) ?>
            </div>
        <?php $page->endContent('bulk-actions') ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= HubGridView::widget([
                    'boxed' => false,
                    'colorize' => true,
                    'dataProvider' => $dataProvider,
                    'filterModel' => $model,
                    'rowOptions' => function ($model) {
                        return GridLegend::create(new HubGridLegend($model))->gridRowOptions();
                    },
                    'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
