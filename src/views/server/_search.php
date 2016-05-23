<?php

/**
 * @var array $states
 * @var \hipanel\widgets\AdvancedSearch $search
 */
use hipanel\modules\client\widgets\combo\ClientCombo;
use hiqdev\combo\StaticCombo;
?>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('client_id')->widget(ClientCombo::classname()) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('seller_id')->widget(ClientCombo::classname()) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('note') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(StaticCombo::classname(), [
        'data' => $states,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => true,
            ]
        ],
    ]) ?>
</div>
