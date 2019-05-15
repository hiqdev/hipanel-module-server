<?php

use hipanel\modules\server\widgets\combo\HubCombo;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;

?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 99, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => Inflector::camel2id(reset($models)->formName()) . '-form',
    'formFields' => $this->context->getFormFields(),
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
                            <?php foreach (array_chunk($switchVariants, 4) as $rows) : ?>
                                <?php foreach ($rows as $variant) : ?>
                                    <div class="col-md-3">
                                        <table class="table table-condensed" style="table-layout: fixed;">
                                            <thead>
                                            <tr>
                                                <th><?= Html::label($model->getAttributeLabel($variant)) ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <?= $form->field($model, "[$i]{$variant}_id")->widget(HubCombo::class, [
                                                        'name' => $variant,
                                                        'hubType' => $this->context->variantMap[$variant] ?? $variant,
                                                    ])->label(false) ?>
                                                </td>
                                                <td>
                                                    <?= $form->field($model, "[$i]{$variant}_port")->label(false) ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endforeach; ?>
                                <div class="clearfix"></div>
                            <?php endforeach; ?>
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