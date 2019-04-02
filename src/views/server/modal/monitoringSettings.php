<?php

use hipanel\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('hipanel:server', 'Monitoring properties');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin([
    'id' => 'mon-form',
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]); ?>

<?= Html::activeHiddenInput($model->monitoringSettings, 'id') ?>

<?= $form->field($model->monitoringSettings, 'emails') ?>
<?= $form->field($model->monitoringSettings, 'minutes') ?>
<?= $form->field($model->monitoringSettings, 'nic_media')->dropDownList($nicMediaOptions) ?>
<?= $form->field($model->monitoringSettings, 'channel_load') ?>
<?= $form->field($model->monitoringSettings, 'watch_trafdown')->checkbox() ?>
<?= $form->field($model->monitoringSettings, 'vcdn_only')->checkbox() ?>
<?= $form->field($model->monitoringSettings, 'comment')->textarea() ?>
<?= Html::submitButton(Yii::t('hipanel:server', 'Save settings'), ['class' => 'btn btn-success']) ?>
<?php $form->end() ?>
