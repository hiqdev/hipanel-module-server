<?php

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */

?>
<?= $search->field('id')->hiddenInput()->label(false) ?>

    <div class="col-md-2">
        <?= $search->field('graph')->dropDownList(
            array_merge(['' => Yii::t('hipanel:server:rrd', 'Index')],
                array_combine((array)$model->graphs, (array)$model->graphs)),
            ['class' => 'form-control input-sm']
        ) ?>
    </div>
    <div class="col-md-2">
        <?= $search->field('period')->dropDownList(
            [
                '1' => Yii::t('hipanel:server', '1 min/px'),
                '5' => Yii::t('hipanel:server', '5 min/px'),
                '60' => Yii::t('hipanel:server', '60 min/px'),
                '720' => Yii::t('hipanel:server', '720 min/px'),
            ],
            ['class' => 'form-control input-sm']
        ) ?>
    </div>
    <div class="col-md-2">
        <?= $search->field('width')->textInput(['placeholder' => '1081', 'class' => 'form-control input-sm']) ?>
    </div>
    <div class="col-md-2">
        <?= $search->field('shift')->textInput(['placeholder' => '0', 'class' => 'form-control input-sm']) ?>
    </div>

<?php
$widgetId = $search->getDivId();

$this->registerJs(<<<JS
    $('#form-$widgetId').on('change', 'select', function (event) {
        $(this).submit();
    });
JS
);
