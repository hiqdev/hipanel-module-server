<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hiqdev\combo\StaticCombo;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
?>
<div class="col-md-4">
    <?= $search->field('name_like') ?>
    <?= $search->field('note') ?>
</div>

<div class="col-md-4">
    <?= $search->field('client_id')->widget(ClientCombo::classname()) ?>
    <?= $search->field('seller_id')->widget(ClientCombo::classname()) ?>
</div>

<div class="col-md-4">
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
