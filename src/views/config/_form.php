<?php

/** @var Config $model */
/** @var yii\widgets\ActiveForm $form */

use hipanel\modules\server\models\Config;
use hipanel\widgets\Box;
use yii\bootstrap\Html;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]); ?>

<?php if ($model->scenario === Config::SCENARIO_UPDATE) : ?>
    <?= Html::activeHiddenInput($model, 'id') ?>
<?php endif; ?>

<div class="row">
    <?php Box::begin(['title' => Yii::t('hipanel:server:config', 'Configuration details')]) ?>
        <div class="col-md-6">
            <?= $form->field($model, 'client_id')->widget(ClientCombo::class) ?>
            <?= $form->field($model, 'name'); ?>
            <?= $form->field($model, 'label'); ?>
            <?= $form->field($model, 'cpu'); ?>
            <?= $form->field($model, 'ram'); ?>
            <?= $form->field($model, 'descr')->textarea(); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'hdd'); ?>
            <?= $form->field($model, 'ssd'); ?>
            <?= $form->field($model, 'traffic'); ?>
            <?= $form->field($model, 'lan'); ?>
            <?= $form->field($model, 'raid'); ?>
            <?= $form->field($model, 'sort_order'); ?>
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

<?php $form->end() ?>
