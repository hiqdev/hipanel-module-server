<?php

/** @var Config $model */
/** @var yii\widgets\ActiveForm $form */

use hipanel\modules\server\models\Config;
use hipanel\widgets\Box;
use yii\bootstrap\Html;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\combo\TariffCombo;
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
    <div class="col-md-12">
        <div class="col-md-6">
            <?php Box::begin([
                'title' => Yii::t('hipanel:server:config', 'Configuration details'),
                'options' => ['class' => 'box-widget']
            ]) ?>
                <?= $form->field($model, 'client_id')->widget(ClientCombo::class) ?>
                <?= $form->field($model, 'name'); ?>
                <?= $form->field($model, 'label'); ?>
                <?= $form->field($model, 'descr')->textarea(); ?>
                <?= $form->field($model, 'us_tariff_id')->widget(TariffCombo::class) ?>
                <?= $form->field($model, 'nl_tariff_id')->widget(TariffCombo::class) ?>
                <?= $form->field($model, 'sort_order'); ?>
            <?php Box::end() ?>

        </div>
        <div class="col-md-6">
            <?php Box::begin([
                'title' => Yii::t('hipanel:server:config', 'Hardware'),
                'options' => ['class' => 'box-widget']
            ]) ?>
                <?= $form->field($model, 'cpu'); ?>
                <?= $form->field($model, 'ram'); ?>
                <?= $form->field($model, 'hdd'); ?>
                <?= $form->field($model, 'ssd'); ?>
                <?= $form->field($model, 'traffic'); ?>
                <?= $form->field($model, 'lan'); ?>
                <?= $form->field($model, 'raid'); ?>
            <?php Box::end() ?>

        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php Box::begin(['options' => ['class' => 'box-widget']]); ?>

        <?= Html::submitButton(Yii::t('hipanel', 'Save'), [
            'class' => 'btn btn-success'
        ]) ?>
        <?= Html::button(Yii::t('hipanel', 'Cancel'), [
            'class' => 'btn btn-default',
            'onclick' => 'history.go(-1)'
        ]) ?>

        <?php Box::end(); ?>
    </div>
</div>

<?php $form->end() ?>
