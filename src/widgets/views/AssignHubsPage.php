<?php

use hipanel\modules\server\assets\AssignHubsColumnReveal;
use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\helpers\AssignHubsGroup;
use hipanel\modules\server\widgets\ApplyToAllWidget;
use hipanel\modules\server\widgets\AssignHubsPage;
use hipanel\modules\server\widgets\combo\HubCombo;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var AssignHubsGroup[] $groups
 * @var AssignHubsForm[] $models
 * @var AssignHubsForm $model
 * @var AssignHubsPage $context
 * @var ActiveForm $form
 * @var AssignHubsPage $context
 */

$renderedAttributes = [];
$context = $this->context;
$this->registerCss(
    <<<CSS
.item li > div {
    display: flex;
    gap: 15px;
    & > div {
      flex: 1 1 0;
      min-width: 0;
    }
}
.item h5 {
  padding-left: 45px;
  padding-bottom: 10px;
  font-weight: bold;
  margin-bottom: 0;
  margin-top: 0;
}
.item .row > div:last-child .row > div:has(input):not(:has(label)) {
  padding-top: 25px;
}
.item .form-group {
  position: relative;
}
CSS
);

AssignHubsColumnReveal::register($this);

?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 999, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => $form->id,
    'formFields' => $context->getAllPossibleFormFieldNames(),
]) ?>

<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <div class="item">
            <?= Html::activeHiddenInput($model, "[$i]id") ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title" style="display: flex; justify-content: space-between; align-items: center;">
                        <span><?= Html::a($model->name, ['view', 'id' => $model->id], ['target' => '_blank']) ?></span>
                        <span class="badge bg-red"><?= mb_strtoupper($model->type) ?></span>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php $groups = $context->splitIntoGroups($model->getHubVariants()) ?>
                        <?php foreach ($groups as $group) : ?>
                            <?php if ($group->notEmpty()) : ?>
                                <div class="col-md-4">
                                    <?php if ($group->hasHeader()): ?>
                                        <h5><?= $group->getLabel() ?></h5>
                                        <ol class="<?= $group->getName() ?>">
                                            <?php foreach ($group->getItems() as $variant) : ?>
                                                <?php $renderedAttributes[] = $variant ?>
                                                <li>
                                                    <div>
                                                        <?php if ($context->hasPort($variant)) : ?>
                                                            <?= $form->field($model, "[$i]{$variant}_id")
                                                                     ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                                     ->label($context->getAttributeLabel($model, $variant)) ?>
                                                            <?= $form->field($model, "[$i]{$variant}_port")
                                                                     ->textInput(['placeholder' => 'Port'])
                                                                     ->label($context->getAttributeLabel($model, $variant . '_port')) ?>
                                                        <?php else : ?>
                                                            <?= $form->field($model, "[$i]{$variant}_id")
                                                                     ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                                     ->label($context->getAttributeLabel($model, $variant)) ?>
                                                        <?php endif ?>
                                                    </div>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    <?php else : ?>
                                        <div>
                                            <?php foreach ($group->getItems() as $variant) : ?>
                                                <?php $renderedAttributes[] = $variant ?>
                                                <div class="row">
                                                    <?php if ($context->hasPort($variant)) : ?>
                                                        <div class="col-md-6">
                                                            <?= $form->field($model, "[$i]{$variant}_id")
                                                                     ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                                     ->label($context->getAttributeLabel($model, $variant)) ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <?= $form->field($model, "[$i]{$variant}_port")
                                                                     ->textInput(['placeholder' => 'Port'])
                                                                     ->label($context->getAttributeLabel($model, $variant . '_port')) ?>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="col-md-12">
                                                            <?= $form->field($model, "[$i]{$variant}_id")
                                                                     ->widget(HubCombo::class, $context->prepareHubComboOptions($variant))
                                                                     ->label($context->getAttributeLabel($model, $variant)) ?>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    <?php endif ?>
                                </div>
                            <?php endif ?>
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
        <?= Html::button(
            Yii::t('hipanel', 'Cancel'),
            ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']
        ) ?>
    </div>
</div>

<?= ApplyToAllWidget::widget([
    'formId' => $form->id,
    'modelFormName' => reset($models)->formName(),
    'attributes' => array_values(array_unique($renderedAttributes)),
]) ?>
