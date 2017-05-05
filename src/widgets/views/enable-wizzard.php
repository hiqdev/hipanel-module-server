<?php

namespace hipanel\server\widgets;

use hipanel\widgets\Box;
use Yii;
?>

<div class="col-md-3">
    <?php $box = Box::begin(['renderBody' => false]) ?>
    <?php $box->beginHeader() ?>
    <?= $box->renderTitle(Yii::t('hipanel:server', 'Server')) ?>
    <?php $box->endHeader() ?>
    <?php $box->beginBody(); ?>
    <?= $form->field($model, 'name')->input('text')->label(Yii::t('hipanel:server', 'Server')) ?>
    <?= $form->field($model, 'dc')->input('text')->label(Yii::t('hipanel:server', 'DC')) ?>
    <?php /* $form->field($model, 'type')->dropDownList($deviceTypes) **/ ?>
    <?php $box->endBody(); ?>
    <?php $box->end() ?>
</div>
<div class="col-md-9">
    <?php $box = Box::begin(['renderBody' => false]) ?>
    <?php $box->beginHeader() ?>
    <?= $box->renderTitle(Yii::t('hipanel:server', 'Services')) ?>
    <?php $box->endHeader() ?>
    <?php $box->beginBody(); ?>
    <?php $box->endBody(); ?>
    <?php $box->end() ?>
</div>

