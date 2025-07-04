<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;
use hipanel\modules\finance\widgets\ConsumptionRepresentationMonthPicker;
use hipanel\modules\server\widgets\combo\ServerStateRefCombo;
use hipanel\modules\server\widgets\combo\ServerTypeRefCombo;
use hipanel\widgets\AdvancedSearch;
use hipanel\widgets\TagsInput;

/**
 * @var AdvancedSearch $search
 */

?>

<?php if (Yii::$app->user->can('server.read-financial-info') || Yii::$app->user->can('server.read-system-info')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('hide_nic', ['options' => ['class' => 'form-group checkbox']])->checkbox(['class' => 'option-input']) ?>
        <?= $search->field('hide_vds', ['options' => ['class' => 'form-group checkbox']])->checkbox(['class' => 'option-input']) ?>
        <?= $search->field('show_deleted', ['options' => ['class' => 'form-group checkbox']])->checkbox(['class' => 'option-input']) ?>
    </div>
<?php endif ?>

<?php if ($this->context->indexPageUiOptionsModel->representation === 'consumption') : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= ConsumptionRepresentationMonthPicker::widget(['model' => $search->model]) ?>
    </div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_dc') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('note_like') ?>
</div>

<?php if (Yii::$app->user->can('server.see-label')): ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('label_like') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->user->can('order.read')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('order_no') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->user->can('hub.read')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('dc_like') ?>
    </div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('ip_like') ?>
</div>
<?php if (Yii::$app->user->canHasSubclients()) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('last_client_id')->widget(ClientCombo::class) ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('client_id')->widget(ClientCombo::class) ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('seller_id')->widget(SellerCombo::class) ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('hwsummary_like') ?>
    </div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->widget(ServerTypeRefCombo::class, [
        'gtype' => 'type,device,server',
        'i18nDictionary' => 'hipanel:server',
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(ServerStateRefCombo::class, [
        'gtype' => 'state,device',
        'i18nDictionary' => 'hipanel:server',
        'multiple' => true,
    ]) ?>
</div>

<?php if (Yii::$app->user->can('server.update')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('tags')->widget(TagsInput::class) ?>
    </div>
<?php endif ?>

<?php if (Yii::$app->user->can('hub.read')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('net_ilike') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('kvm_ilike') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('pdu_ilike') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('rack_ilike') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('rack_inilike') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('mac_ilike') ?>
    </div>
<?php endif ?>

<?php if (Yii::$app->user->can('plan.read')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('tariff_like') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('wizzarded_eq')->dropDownList([
            null => Yii::t('hipanel:server', 'All'),
            0 => Yii::t('hipanel:server', 'Not wizzarded'),
            1 => Yii::t('hipanel:server', 'Wizzarded'),
        ]) ?>
    </div>
<?php endif ?>
