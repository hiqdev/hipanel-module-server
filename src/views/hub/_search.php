<?php declare(strict_types=1);

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\ConsumptionRepresentationMonthPicker;
use hipanel\widgets\AdvancedSearch;
use hipanel\widgets\TagsInput;
use hiqdev\combo\StaticCombo;

/**
 * @var AdvancedSearch $search
 * @var array $types
 */


?>

<?php if ($this->context->indexPageUiOptionsModel->representation === 'consumption') : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= ConsumptionRepresentationMonthPicker::widget(['model' => $search->model]) ?>
    </div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_inilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('inn_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('ip_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('mac_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('order_no_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type_id')->dropDownList($types, ['prompt' => '--']) ?>
</div>

<?php if (Yii::$app->user->can('access-subclients')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('buyer')->widget(ClientCombo::class) ?>
    </div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('tariff_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('rack_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('rack_inilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state_in')->widget(StaticCombo::class, [
        'multiple' => true,
        'type' => 'server/hub',
        'data' => $search->model->getStates(),
    ]) ?>
</div>

<?php if (Yii::$app->user->can('hub.update')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('tags')->widget(TagsInput::class) ?>
    </div>
<?php endif ?>
