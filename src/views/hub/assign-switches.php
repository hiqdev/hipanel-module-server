<?php

use hipanel\modules\server\widgets\AssignSwitchesPage;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('hipanel:server:hub', 'Edit switches');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
if (count($models) === 1) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'assign-switches-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-switches-form', 'scenario' => 'default']),
]) ?>

<?= AssignSwitchesPage::widget([
    'models' => $models,
    'switchVariants' => ['net', 'kvm', 'pdu', 'rack', 'console'],
    'form' => $form,
]) ?>
