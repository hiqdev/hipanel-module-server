<?php

/** @var array $snmpOptions */
/** @var array $digitalCapacityOptions */

/** @var array $nicMediaOptions */

use hipanel\helpers\Url;
use hipanel\modules\server\widgets\combo\ServerCombo;
use hipanel\widgets\PasswordInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'hub-options-form',
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]); ?>

<?= Html::activeHiddenInput($model, 'id') ?>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'inn') ?>
                        <?= $form->field($model, 'model') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'login') ?>
                        <?= $form->field($model, 'password')->widget(PasswordInput::class) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'user_login') ?>
                        <?= $form->field($model, 'user_password')->widget(PasswordInput::class) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'ports_num') ?>
                        <?= $form->field($model, 'traf_server_id')->widget(ServerCombo::class, [
                            'pluginOptions' => [],
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'vlan_server_id')->widget(ServerCombo::class) ?>
                        <?= $form->field($model, 'community') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'snmp_version_id')->dropDownList($snmpOptions, ['prompt' => '--']) ?>
                        <?= $form->field($model, 'digit_capacity_id')->dropDownList($digitalCapacityOptions, ['prompt' => '--']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'nic_media')->dropDownList($nicMediaOptions, ['prompt' => '--']) ?>
                        <?= $form->field($model, 'base_port_no') ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'oob_key') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>

<?php $form->end() ?>
