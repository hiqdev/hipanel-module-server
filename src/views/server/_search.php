<?php

/**
 * @var array
 * @var \hipanel\widgets\AdvancedSearch $search
 */
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;
use hipanel\widgets\RefCombo;

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field(Yii::$app->user->can('support') ? 'label_like' : 'note_like') ?>
</div>

<?php if (Yii::$app->user->can('manage')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('order_no') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->user->can('support')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('dc_like') ?>
    </div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('ip_like') ?>
</div>

<?php if (Yii::$app->user->can('support')) : ?>
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
    <?= $search->field('type')->widget(RefCombo::class, [
        'gtype' => 'type,device,server',
        'i18nDictionary' => 'hipanel:server',
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(RefCombo::class, [
        'gtype' => 'state,device',
        'i18nDictionary' => 'hipanel:server',
        'multiple' => true,
    ]) ?>
</div>

<?php if (Yii::$app->user->can('support')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('net_like') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('kvm_like') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('pdu_like') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('rack_like') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('mac_like') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('tariff_like') ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('wizzarded_eq')->dropDownList([
            null    => Yii::t('hipanel:server', 'All'),
            0       => Yii::t('hipanel:server', 'Not wizzarded'),
            1       => Yii::t('hipanel:server', 'Wizzarded'),
        ]) ?>
    </div>
<?php endif ?>
