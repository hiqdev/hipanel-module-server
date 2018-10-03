<?php

use hipanel\helpers\Url;
use hipanel\modules\server\widgets\combo\HubCombo;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/** @var \hipanel\modules\server\models\Server $model */
/** @var \hipanel\modules\server\models\Server[] $models */

$this->title = Yii::t('hipanel:server', 'Assign hubs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'assign-hubs-form',
    'layout' => 'inline',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-assign-hubs-form', 'scenario' => 'default']),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 99, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => 'assign-hubs-form',
    'formFields' => [
        'id',
        'rack_id',
        'rack_port',
        'net_id',
        'net_port',
        'pdu_id',
        'pdu_port',
        'ipmi_id',
        'ipmi_port',
        'kvm_id',
        'kvm_port',
        'nic2_id',
        'nic2_port',
        'pdu2_port',
        'pdu2_port',
    ],
]) ?>
<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <div class="item">
            <?= Html::activeHiddenInput($model, "[$i]id") ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $model->name ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <?= Html::label($model->getAttributeLabel('rack')) ?>
                            <br>
                            <?= $form->field($model, "[$i]rack_id")->widget(HubCombo::class, ['hubType' => HubCombo::RACK]) ?>
                            <?= $form->field($model, "[$i]rack_port") ?>
                        </div>
                        <div class="col-xs-3">
                            <?= Html::label($model->getAttributeLabel('net')) ?>
                            <br>
                            <?= $form->field($model, "[$i]net_id")->widget(HubCombo::class, ['hubType' => HubCombo::NET]) ?>
                            <?= $form->field($model, "[$i]net_port") ?>
                        </div>
                        <div class="col-xs-3">
                            <?= Html::label($model->getAttributeLabel('pdu')) ?>
                            <br>
                            <?= $form->field($model, "[$i]pdu_id")->widget(HubCombo::class, ['hubType' => HubCombo::PDU]) ?>
                            <?= $form->field($model, "[$i]pdu_port") ?>
                        </div>
                        <div class="col-xs-3">
                            <?= Html::label($model->getAttributeLabel('ipmi')) ?>
                            <br>
                            <?= $form->field($model, "[$i]ipmi_id")->widget(HubCombo::class, ['hubType' => HubCombo::IPMI]) ?>
                            <?= $form->field($model, "[$i]ipmi_port") ?>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 2rem">
                        <div class="col-xs-3">
                            <?= Html::label($model->getAttributeLabel('kvm')) ?>
                            <br>
                            <?= $form->field($model, "[$i]kvm_id")->widget(HubCombo::class, ['hubType' => HubCombo::KVM]) ?>
                            <?= $form->field($model, "[$i]kvm_port") ?>
                        </div>
                        <div class="col-xs-3">
                            <?= Html::label($model->getAttributeLabel('nic2')) ?>
                            <br>
                            <?= $form->field($model, "[$i]nic2_id")->widget(HubCombo::class, ['hubType' => HubCombo::NET]) ?>
                            <?= $form->field($model, "[$i]nic2_port") ?>
                        </div>
                        <div class="col-xs-3">
                            <?= Html::label($model->getAttributeLabel('pdu2')) ?>
                            <br>
                            <?= $form->field($model, "[$i]pdu2_id")->widget(HubCombo::class, ['hubType' => HubCombo::PDU]) ?>
                            <?= $form->field($model, "[$i]pdu2_port") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php DynamicFormWidget::end() ?>
<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

