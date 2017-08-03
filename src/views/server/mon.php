<?php

use hipanel\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('hipanel:server', 'Monitoring properties');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'id' => 'mon-form',
            'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
        ]); ?>

        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel:server', 'Change monitoring properties') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'emails') ?>
                <?= $form->field($model, 'minutes') ?>
                <?= $form->field($model, 'nic_media')->dropDownList([]) ?>
                <?= $form->field($model, 'channel_load') ?>
                <?= $form->field($model, 'watch_trafdown')->checkbox() ?>
                <?= $form->field($model, 'vcdn_only')->checkbox() ?>
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php $form->end() ?>
    </div>
    <div class="col-md-6">
        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel:server', 'History of changes') ?>
                </h3>
            </div>
            <div class="box-body">
            </div>
        </div>
    </div>
</div>
