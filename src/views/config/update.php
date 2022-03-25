<?php

/** @var \hipanel\modules\server\forms\ConfigForm $model */
/** @var \hipanel\modules\server\forms\ConfigForm[] $models */

$this->title = Yii::t('hipanel', 'Edit');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('hipanel:server:config', 'Configs'),
    'url' => ['index'],
];
if (count($models) === 1) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models'])) ?>
