<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;
use hipanel\modules\server\widgets\combo\ServerStateRefCombo;
use hipanel\modules\server\widgets\combo\ServerTypeRefCombo;
use hipanel\widgets\AdvancedSearch;

/**
 * @var AdvancedSearch $search
 */

?>

<?php if (Yii::$app->user->can('support')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('hide_nic')->checkbox(['class' => 'option-input']) ?>
        <?= $search->field('hide_vds')->checkbox(['class' => 'option-input']) ?>
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

<?php if (Yii::$app->user->can('manage')) : ?>
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

<?php if (Yii::$app->user->can('access-subclients')) : ?>
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
