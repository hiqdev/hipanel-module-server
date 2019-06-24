<?php

/** @var Config $model */

use hipanel\modules\server\models\Config;
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\widgets\Box;
use yii\bootstrap\Html;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\combo\TariffCombo;
use hipanel\helpers\Url;
use yii\widgets\ActiveForm;

$model->servers = array_unique($model->servers ? array_map('trim', explode(',', $model->servers)) : []);

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
                <?= $form->field($model, 'servers')->widget(DestinationCombo::class, [
                    'name' => 'servers',
                    'filter' => [],
                    'pluginOptions' => [],
                    'multiple' => true,
                    'selectAllButton' => false,
                    'primaryFilter' => 'name_inilike',
                ]) ?>

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
