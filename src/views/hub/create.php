<?php

/* @var  array $types */

$this->title = Yii::t('hipanel:server:hub', 'Create Switch');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact('models', 'model', 'types')) ?>
