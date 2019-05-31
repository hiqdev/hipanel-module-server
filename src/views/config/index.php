<?php
/**
 * @var IndexPageUiOptions $uiModel
 * @var SaleRepresentations $representationCollection
 * @var ActiveDataProvider $dataProvider
 */
use hipanel\models\IndexPageUiOptions;
use hipanel\modules\finance\grid\SaleRepresentations;
use hipanel\modules\server\grid\ConfigGridView;
use hipanel\widgets\IndexPage;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

$this->title = Yii::t('hipanel.integrations', 'Configuration');
$this->params['breadcrumbs'][] = $this->title;
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), []))
    ? Yii::t('hipanel', 'filtered list')
    : Yii::t('hipanel', 'full list');

$user = Yii::$app->user;
?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

<?php $page->beginContent('main-actions') ?>
<?= Html::a(Yii::t('hipanel', 'Create'), 'create', [
    'class' => 'btn btn-sm btn-success'
]) ?>
<?php $page->endContent() ?>

<?php $page->beginContent('bulk-actions') ?>
    <?= $page->renderBulkDeleteButton('@config/delete')?>
<?php $page->endContent() ?>

<?php $page->beginContent('table') ?>
    <?php $page->beginBulkForm() ?>
        <?= ConfigGridView::widget([
            'boxed' => false,
            'dataProvider' => $dataProvider,
            'filterModel'  => $model,
            'columns' => $representationCollection
                ->getByName($uiModel->representation)
                ->getColumns(),
        ]) ?>
    <?php $page->endBulkForm() ?>
<?php $page->endContent() ?>
<?php $page->end() ?>
