<?php

/**
 * @var array $states
 * @var \hipanel\widgets\AdvancedSearch $search
 */
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\widgets\RefCombo;
?>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('client_id')->widget(ClientCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('seller_id')->widget(ClientCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('note') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->widget(RefCombo::class, [
        'gtype' => 'type,device,server',
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(RefCombo::class, [
        'gtype' => 'state,device',
        'multiple' => true,
    ]) ?>
</div>
