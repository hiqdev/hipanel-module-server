<?php

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
if (count($models) === 1) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', ['models' => $models, 'model' => $model, 'types' => $types]) ?>
