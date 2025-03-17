<?php

/** @var Server $model */

use hipanel\helpers\Url;
use hipanel\modules\server\models\Server;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('hipanel:server', 'Software properties');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin([
    'id' => 'sw-form',
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]); ?>

<?= Html::activeHiddenInput($model->softwareSettings, 'id') ?>

<?= $form->field($model->softwareSettings, 'os') ?>
<?= $form->field($model->softwareSettings, 'version') ?>
<?= $form->field($model->softwareSettings, 'delivery_time')
    ->input('number', ['step' => 1, 'min' => 0])
    ->hint(Yii::t('hipanel:server', 'The unit of measurement is an hour.')) ?>
<?= $form->field($model->softwareSettings, 'virtual_switch')->checkbox() ?>
<?= $form->field($model->softwareSettings, 'ignore_ip_mon')->checkbox() ?>
<?= $form->field($model->softwareSettings, 'ip_mon_comment') ?>
<?= $form->field($model->softwareSettings, 'bw_limit') ?>
<?= $form->field($model->softwareSettings, 'bw_group') ?>
<?= $form->field($model->softwareSettings, 'failure_contacts')->textarea() ?>
<?= $form->field($model->softwareSettings, 'info') ?>
<?= Html::submitButton(Yii::t('hipanel:server', 'Save settings'), ['class' => 'btn btn-success']) ?>

<?php $form::end() ?>
