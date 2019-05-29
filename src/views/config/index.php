<?php

use hipanel\modules\server\grid\ConfigGridView;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;


$this->title = Yii::t('hipanel.integrations', 'Configuration');
$this->params['breadcrumbs'][] = $this->title;
$subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');

?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

<?php $page->beginContent('table') ?>
    <?php $page->beginBulkForm() ?>
        <?= ConfigGridView::widget([
            'boxed' => false,
            'dataProvider' => $dataProvider,
            'filterModel'  => $model,
            'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
        ]) ?>
    <?php $page->endBulkForm() ?>
<?php $page->endContent() ?>
<?php $page->end() ?>
