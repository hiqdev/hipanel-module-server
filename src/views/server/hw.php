<?php

use hipanel\helpers\Url;
use hipanel\modules\server\grid\ServerGridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:server', 'Hardware properties');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-4">
        <?php $form = ActiveForm::begin([
            'id' => 'hw-form',
            'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
        ]); ?>

        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel:server', 'Change hardware properties') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'summary') ?>
                <?= $form->field($model, 'order_no') ?>
                <?= $form->field($model, 'brand') ?>
                <?= $form->field($model, 'box') ?>
                <?= $form->field($model, 'cpu') ?>
                <?= $form->field($model, 'ram') ?>
                <?= $form->field($model, 'motherboard') ?>
                <?= $form->field($model, 'hdd') ?>
                <?= $form->field($model, 'hotswap') ?>
                <?= $form->field($model, 'raid') ?>
                <?= $form->field($model, 'units') ?>
                <?= $form->field($model, 'note') ?>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php $form->end() ?>
    </div>
    <div class="col-md-4">
        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel:server', 'Server HW') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= ServerGridView::detailView([
                    'model' => $model,
                    'boxed' => false,
                    'columns' => [
                        'server'
                    ]
                ]); ?>
            </div>
        </div>
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
