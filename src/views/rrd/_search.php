<?php

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */
use yii\helpers\Html;

?>
<?= $search->field('id')->hiddenInput()->label(false) ?>

<div class="col-md-12">
    <?= $search->field('graph')->widget(\yii\bootstrap\ToggleButtonGroup::class, [
        'type' => 'radio',
        'items' => array_merge(['' => Yii::t('hipanel/server/rrd', 'Index')], array_combine((array) $model->graphs, (array) $model->graphs)),
        'labelOptions' => [
            'class' => 'btn btn-default'
        ],
        'options' => [
            'style' => 'display: block'
        ]
    ]) ?>
</div>

<div class="col-md-3">
    <?= $search->field('period')->widget(\yii\bootstrap\ToggleButtonGroup::class, [
        'type' => 'radio',
        'items' => ['1' => '1 min/px', '5' => '5 min/px', '60' => '1 hour/px', '720' => '12 hours/px'],
        'labelOptions' => [
            'class' => 'btn btn-default',
        ],
        'options' => [
            'style' => 'display: block'
        ]
    ]) ?>
</div>
<div class="col-md-2">
    <?= $search->field('width') ?>
</div>
<div class="col-md-2">
    <?= $search->field('shift') ?>
</div>
