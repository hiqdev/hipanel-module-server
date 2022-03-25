<?php

/** @var Config $model */

use hipanel\modules\server\models\Config;
use hipanel\widgets\Box;
use yii\bootstrap\Html;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\combo\TariffCombo;
use hipanel\helpers\Url;
use yii\widgets\ActiveForm;

$model->servers = array_unique($model->servers ? array_map('trim', explode(',', $model->servers)) : []);
$model->profile_ids = $model->profile_ids ? array_map('trim', explode(',', $model->profile_ids)) : [];

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
        <div class="col-md-6">
            <?php Box::begin([
                'title' => Yii::t('hipanel:server:config', 'Configuration details'),
                'options' => ['class' => 'box-widget']
            ]) ?>
                <?= $form->field($model, 'client_id')->widget(ClientCombo::class) ?>
                <?= $form->field($model, 'name'); ?>
                <?= $form->field($model, 'label'); ?>
                <?= $form->field($model, 'descr')->textarea(); ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'us_tariff_id')->widget(TariffCombo::class) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'us_old_price') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'nl_tariff_id')->widget(TariffCombo::class) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'nl_old_price') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'nl_low_limit')->input('number', ['min' => 0]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'us_low_limit')->input('number', ['min' => 0]) ?>
                </div>
            </div>
            <?= $form->field($model, 'sort_order'); ?>
            <?php Box::end() ?>
        </div>
</div>


<?= Html::submitButton(Yii::t('hipanel', 'Save'), [
    'class' => 'btn btn-success',
]) ?>
&nbsp;
<?= Html::button(Yii::t('hipanel', 'Cancel'), [
    'class' => 'btn btn-default',
    'onclick' => 'history.go(-1)',
]) ?>


<?php $form->end() ?>
