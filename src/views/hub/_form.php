<?php

use hipanel\helpers\Url;
use hipanel\modules\server\models\Hub;
use hipanel\widgets\DynamicFormCopyButton;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var array $types */
/** @var array $models */
/** @var Hub $model */

?>

<div class="row">
    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
        'action' =>  Url::toRoute([$model->scenario]),
    ]); ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 999, // the maximum times, an element can be cloned (default 999)
        'min' => 1, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $model,
        'formId' => 'dynamic-form',
        'formFields' => [
            'name',
            'type_id',
            'mac',
            'inn',
            'ip',
            'note',
            'model',
            'order_no',
        ],
    ]) ?>

    <div class="container-items">
        <?php foreach ($models as $i => $model) : ?>
            <div class="item col-md-12">
                <?php if (!$model->isNewRecord) : ?>
                    <?= Html::activeHiddenInput($model, "[$i]id") ?>
                <?php endif; ?>
                <div class="box box-solid">
                    <?php if ($model->isNewRecord) : ?>
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm add-item">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <?= DynamicFormCopyButton::widget() ?>
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <?= $form->field($model, "[$i]name") ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($model, "[$i]inn") ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($model, "[$i]type_id")->dropDownList($types, ['prompt' => '--']) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, "[$i]mac")->hint(Yii::t('hipanel:server:hub', 'Example: {0}', ['00:27:0e:2a:b9:aa, 00-27-0E-2A-B9-AA, 0.27.e.2a.b9.aa ...'])) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, "[$i]ip") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <?= $form->field($model, "[$i]model") ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($model, "[$i]order_no") ?>
                            </div>
                            <div class="col-md-8">
                                <?= $form->field($model, "[$i]note") ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php DynamicFormWidget::end() ?>

    <div class="col-md-12 space-sm">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>
