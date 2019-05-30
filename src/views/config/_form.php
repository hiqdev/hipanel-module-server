<?php

/** @var \hipanel\modules\server\models\Config $model */
/** @var yii\widgets\ActiveForm $form */

use hipanel\widgets\Box;
use yii\bootstrap\Html;
use hipanel\modules\client\widgets\combo\ClientCombo;

?>

<div class="row">
    <?php Box::begin(['title' => Yii::t('hipanel:server:config', 'Configuration details')]) ?>

    <div class="col-md-6">
        <?= $form->field($model, 'client_id')->widget(ClientCombo::class) ?>
        <?= $form->field($model, 'name'); ?>
        <?= $form->field($model, 'subname'); ?>
        <?= $form->field($model, 'location'); ?>
        <?= $form->field($model, 'cpu'); ?>
        <?= $form->field($model, 'ram'); ?>
        <?= $form->field($model, 'hdd'); ?>
        <?= $form->field($model, 'enabled')->checkbox(); ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'traffic'); ?>
        <?= $form->field($model, 'lan'); ?>
        <?= $form->field($model, 'raid'); ?>
        <?= $form->field($model, 'sort_order'); ?>
        <?= $form->field($model, 'price'); ?>
        <?= $form->field($model, 'last_price'); ?>
        <?= $form->field($model, 'description'); ?>
    </div>
    <?php Box::end() ?>

</div>

<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
