<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\TariffCombo;
use hipanel\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'options' => ['id' => $model->scenario . '-form'],
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>

<?= Html::activeHiddenInput($model, 'id') ?>
<?= $form->field($model, 'client_id')->widget(ClientCombo::class, ['formElementSelector' => '.form-group']) ?>

<?= $form->field($model, 'tariff_id')->widget(TariffCombo::class, [
    'formElementSelector' => '.form-group',
    'tariffType' => 'server',
]) ?>
<?= $form->field($model, 'sale_time')->widget(DateTimePicker::class, [
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd hh:ii:ss',
    ],
    'options' => [
        'value' => Yii::$app->formatter->asDatetime($model->sale_time, 'php:Y-m-d H:i:s'),
    ],
]) ?>
<?= $form->field($model, 'move_accounts')->checkbox() ?>

<hr>

<?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?> &nbsp;
<?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
<?php $form::end() ?>
