<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\TariffCombo;
use hipanel\widgets\DateTimePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => $model->scenario . '-form',
    ],
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>

    <?= $form->field($model, 'client')->widget(ClientCombo::class) ?>
    <?= $form->field($model, 'tariff')->widget(TariffCombo::class, [
        'tariffType' => 'server',
    ]) ?>
    <?= $form->field($model, 'sale_time')->widget(DateTimePicker::class, [
        'pluginOptions' => [
            'autoclose' => true,
        ],
    ]) ?>

    <hr>

    <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?> &nbsp;
    <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
<?php $form::end() ?>
