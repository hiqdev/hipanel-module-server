<?php

use hipanel\helpers\Url;
use hipanel\widgets\DynamicFormWidget;
use hipanel\widgets\DynamicFormCopyButton;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/** @var \hipanel\modules\server\forms\ServerForm $model */
/** @var \hipanel\modules\server\forms\ServerForm[] $models */

$model->ips = is_array($model->ips) ? implode(',', $model->ips) : $model->ips;
?>
<?php $form = ActiveForm::begin([
    'id' => 'server-dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-crud-form', 'scenario' => $model->scenario]),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 99, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => $model,
    'formId' => 'server-dynamic-form',
    'formFields' => [
        'id',
        'server',
        'dc',
        'type',
        'ips',
        'mac',
        'order_no',
        'label',
        'hwsummary',
    ],
]) ?>

<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <div class="item">
            <?php if (!$model->isNewRecord) : ?>
                <?= Html::activeHiddenInput($model, "[$i]id") ?>
            <?php endif; ?>

            <div class="box box-widget">
                <?php if ($model->isNewRecord) : ?>
                    <div class="box-header with-border">
                        <h3 class="box-title">&nbsp;</h3>
                        <div class="box-tools pull-right">
                            <div class="btn-group">
                                <button type="button" class="add-item btn btn-success btn-sm"><i
                                            class="glyphicon glyphicon-plus"></i></button>
                                <?= DynamicFormCopyButton::widget() ?>
                                <button type="button" class="remove-item btn btn-danger btn-sm"><i
                                            class="glyphicon glyphicon-minus"></i></button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <?= $form->field($model, "[$i]server") ?>
                                </div>
                                <div class="col-md-3">
                                    <?= $form->field($model, "[$i]dc") ?>
                                </div>
                                <div class="col-md-1">
                                    <?= $form->field($model, "[$i]type")->dropDownList($model->typeOptions, ['prompt' => '--']) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= $form->field($model, "[$i]ips") ?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($model, "[$i]mac") ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <?= $form->field($model, "[$i]order_no") ?>
                                </div>
                                <div class="col-md-3">
                                    <?= $form->field($model, "[$i]label") ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, "[$i]hwsummary") ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<?php DynamicFormWidget::end() ?>

<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
