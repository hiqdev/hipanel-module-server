<?php

use hipanel\modules\server\grid\HubGridLegend;
use hipanel\modules\server\grid\HubGridView;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:server', 'Switches');
$this->params['breadcrumbs'][] = $this->title;
$subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->registerCss("
    .sale-flex-cnt {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        flex-wrap: wrap;
    }
");

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->setSearchFormData(['types' => $types]) ?>

        <?php $page->beginContent('main-actions') ?>
            <?php if (Yii::$app->user->can('admin') or 1) : ?>
                <?= Html::a(Yii::t('hipanel:server:hub', 'Create switch'), ['@hub/create'], ['class' => 'btn btn-sm btn-success']) ?>
            <?php endif; ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('legend') ?>
            <?= GridLegend::widget(['legendItem' => new HubGridLegend($model)]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('show-actions') ?>
            <?= $page->renderLayoutSwitcher() ?>
            <?= $page->renderSorter(['attributes' => ['id']]) ?>
            <?= $page->renderPerPage() ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('bulk-actions') ?>
            <?= $page->renderBulkButton('<i class="fa fa-pencil"></i>&nbsp;&nbsp;' . Yii::t('hipanel', 'Update'), 'update')?>
        <?php $page->endContent('bulk-actions') ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= HubGridView::widget([
                    'boxed' => false,
                    'dataProvider' => $dataProvider,
                    'filterModel' => $model,
                    'rowOptions' => function ($model) {
                        return GridLegend::create(new HubGridLegend($model))->gridRowOptions();
                    },
                    'columns' => [
                        'checkbox',
                        'inn',
                        'buyer',
                        'actions',
                        'model',
                        'switch',
                        'type',
                        'ip',
                        'mac',
                    ],
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
