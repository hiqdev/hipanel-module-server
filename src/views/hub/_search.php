<?php
/** @var array $types */
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_like') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('inn') ?>
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

