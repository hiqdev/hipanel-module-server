<?php

use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\widgets\AssignSwitchesPage;
use hipanel\modules\server\widgets\combo\HubCombo;
use hipanel\widgets\ApplyToAllWidget;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\View;

/**
 * @var View $this
 * @var AssignHubsForm[] $models
 * @var AssignSwitchesPage $context
 * @var ActiveForm $form
 * @var AssignSwitchesPage $context
 */

$renderedAttributes = [];
$context = $this->context;

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
    'formFields' => $context->getFormFields(),
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
                        <?php foreach (array_chunk($model->getSwitchVariants(), 4) as $rows) : ?>
                            <?php foreach ($rows as $variant) : ?>
                                <?php $renderedAttributes[] = $variant ?>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="<?= implode(" ",
                                            array_filter([
                                                'col-md-12',
                                                $context->hasPort($variant) ? 'col-lg-8' : null,
                                            ])) ?>">
                                            <?= $form->field($model,
                                                "[$i]{$variant}_id")->widget(HubCombo::class,
                                                array_filter([
                                                    'name' => $variant,
                                                    'url' => $variant === HubCombo::JBOD ? '/server/server/index' : null,
                                                    'type' => $variant === HubCombo::JBOD ? '/server/server' : null,
                                                    'hubType' => $context->variantMap[$variant] ?? $variant,
                                                ]))->label($model->getAttributeLabel($variant)) ?>
                                        </div>
                                        <?php if ($context->hasPort($variant)) : ?>
                                            <div class="col-lg-4 col-md-12">
                                                <td style="width: 20%">
                                                    <?= $form->field($model, "[$i]{$variant}_port")->label("&nbsp;") ?>
                                                </td>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                            <div class="clearfix"></div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<?php DynamicFormWidget::end() ?>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'),
            ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>

<?= ApplyToAllWidget::widget([
    'models' => $models,
    'attributes' => array_values(array_unique($renderedAttributes)),
]) ?>
