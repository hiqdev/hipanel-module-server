<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'id' => 'mon-form',
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]); ?>

<?= Html::activeHiddenInput($model->mailSettings, 'id') ?>

<?= $form->field($model->mailSettings, 'per_hour_limit') ?>
<?= $form->field($model->mailSettings, 'block_sending')->checkbox() ?>

<?= Html::submitButton(Yii::t('hipanel:server', 'Save settings'), ['class' => 'btn btn-success btn-block']) ?>

<?php $form::end() ?>
