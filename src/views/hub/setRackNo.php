<?php

use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\widgets\SetRackNo;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/** @var AssignHubsForm $model */
/** @var AssignHubsForm[] $models */

$this->title = Yii::t('hipanel:server', 'Set Rack No.');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$model->rack_id = $model->rack_port = null;
?>

<?php $form = ActiveForm::begin([
    'id' => 'set-rack-no-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-hw-form', 'scenario' => $model->scenario]),
]) ?>

<?= SetRackNo::widget(compact('model', 'models', 'form')) ?>

<?php ActiveForm::end() ?>
