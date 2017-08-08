<?php

use hipanel\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('hipanel:server', 'Software properties');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'id' => 'sw-form',
            'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
        ]); ?>

        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel:server', 'Change software properties') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'os') ?>
                <?= $form->field($model, 'version') ?>
                <?= $form->field($model, 'virtual_switch')->checkbox() ?>
                <?= $form->field($model, 'ignore_ip_mon')->checkbox() ?>
                <?= $form->field($model, 'ip_mon_comment') ?>
                <?= $form->field($model, 'bw_limit') ?>
                <?= $form->field($model, 'bw_group') ?>
                <?= $form->field($model, 'failure_contacts')->textarea() ?>
                <?= $form->field($model, 'info') ?>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php $form->end() ?>
    </div>
</div>
