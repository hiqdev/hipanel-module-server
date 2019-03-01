<?php
/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var array $types
 */
use hipanel\modules\client\widgets\combo\ClientCombo;
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_inilike') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('inn_ilike') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('ip') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('mac') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('order_no') ?>
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
    <?= $search->field('tariff') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('rack_like') ?>
</div>
