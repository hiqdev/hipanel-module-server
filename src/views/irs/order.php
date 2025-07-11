<?php

use hipanel\modules\server\assets\irs\IRSAsset;
use hipanel\modules\server\forms\IRSOrder;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * @var IRSOrder $order
 * @var \hipanel\modules\ticket\models\Thread $ticket
 */

IRSAsset::register($this);

$this->title = Yii::t('hipanel.server.irs', 'Order form');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.server.irs', 'Available dedicated servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::hiddenInput('order-form-model', Json::encode($order->toArray()), ['id' => 'order-form-model']) ?>
<?= Html::hiddenInput('order-form-options', Json::encode($order->toOptions()), ['id' => 'order-form-options']) ?>

<div id="irs-app" v-cloak>
    <p class="bg-warning" v-if="orderOptions.length === 0" style="padding: 15px;"><?= Yii::t('hipanel.server.irs',
            'There are no suitable servers at the moment') ?></p>
    <transition name="slide-down" mode="out-in" v-else>
        <div class="row" v-if="submitted">
            <div class="col-md-3 col-md-offset-4">
                <div class="box box-success box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="margin: 0 auto; width: 100%; text-align: center;">
                            <?= Yii::t('hipanel.server.irs', 'Your order has been successfully submitted!') ?>
                        </h3>
                    </div>

                    <div class="box-body">
                        <p v-if="ticketId">
                            <?= Yii::t('hipanel.server.irs', 'Your ticket number is') ?>
                            <a :href="linkToTicket">#{{ ticketId }}</a>
                        </p>
                        <p><?= Yii::t('hipanel.server.irs', 'Thank you!') ?></p>
                        <p><?= Yii::t('hipanel.server.irs', 'Our Manager will contact you soon.') ?></p>
                    </div>

                </div>
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'method' => 'POST',
            'enableClientScript' => false,
            'options' => [
                'ref' => 'orderForm',
                'v-on:submit.prevent' => 'handleSubmit',
                'v-else' => true,
            ],
        ]) ?>

        <div class="row">
            <div class="col-md-6 config">

                <ul class="list-group">
                    <transition name="list">
                        <li class="list-group-item" v-if="order.upgrade">
                            <?= $form->field($order, 'ram')->dropDownList($order->getItems('ram'), ['v-model' => 'order.ram']) ?>
                            <div class="text">{{ showPrice('ram') }}</div>
                        </li>
                    </transition>
                    <transition name="list">
                        <li class="list-group-item bg-gray-light text-bold" v-if="order.upgrade">
                            <?= Yii::t('hipanel.server.irs', 'Disks') ?>
                        </li>
                    </transition>
                    <transition name="list">
                        <li class="list-group-item" v-if="order.upgrade">
                            <?= $form->field($order, 'raid')->dropDownList($order->getItems('raid'), ['v-model' => 'order.raid']) ?>
                            <div class="text">{{ showPrice('raid') }}</div>
                        </li>
                    </transition>
                    <transition name="list">
                        <li class="list-group-item" v-if="order.upgrade">
                            <?= $form->field($order, 'hdd')
                                ->dropDownList($order->getItems('hdd'), ['v-model' => 'order.hdd'])
                                ->hint('{{ showHint("hdd") }}') ?>
                            <div class="text">{{ showPrice('hdd') }}</div>
                        </li>
                    </transition>
                    <transition name="list">
                        <li class="list-group-item" v-if="order.upgrade">
                            <?= $form->field($order, 'ssd')
                                ->dropDownList($order->getItems('ssd'), ['v-model' => 'order.ssd'])
                                ->hint('{{ showHint("ssd") }}') ?>
                            <div class="text">{{ showPrice('ssd') }}</div>
                        </li>
                    </transition>
                    <li class="list-group-item">
                        <?= $form->field($order, 'ip')->dropDownList($order->getItems('ip'),
                            ['v-model' => 'order.ip', ':disabled' => '!order.upgrade']) ?>
                        <div class="text"><?= "{{ showPrice('ip') }}" ?></div>
                    </li>
                    <li class="list-group-item">
                        <?= $form->field($order, 'administration')
                            ->dropDownList($order->getItems('administration'), [
                                'v-model' => 'order.administration',
                                ':disabled' => '!order.upgrade',
                            ]) ?>
                        <div class="text"><?= "{{ showPrice('administration') }}" ?></div>
                    </li>
                    <li class="list-group-item">
                        <?= $form->field($order, 'os')->dropDownList($order->getItems('os'),
                            ['v-model' => 'order.os', ':disabled' => '!order.upgrade']) ?>
                        <div class="text"><?= "{{ showPrice('os') }}" ?></div>
                    </li>
                    <transition name="list">
                        <li class="list-group-item" v-if="order.upgrade">
                            <?= $form->field($order, 'diskPartitioningComment')->textarea([
                                'rows' => 10,
                                'value' => <<<TXT
Recommended disk partitioning scheme. Note that this is just a template, and you can modify it to suit the needs.

1. EFI System Partition (ESP):
- Mount point: /boot/efi
- File system type: FAT32
- Recommended size: 512 MB
- Note: Flag: boot

2. Root:
- Mount point: /
- File system type: ext4
- Recommended size: Remaining disk space minus swap
- Note: Primary partition

3. Swap:
- Mount point: [none]
- File system type: swap
- Recommended size: Equal to RAM size (no more than 4 GB)
- Note: Swap partition
TXT
                                ,
                            ]) ?>
                        </li>
                    </transition>
                    <li class="list-group-item bg-gray-light text-bold"><?= Yii::t('hipanel.server.irs', 'Traffic') ?></li>
                    <li class="list-group-item">
                        <div>
                            <div class="form-group" style="margin-bottom: 1rem">
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" v-model="trafficUnit" value="mbps">
                                        Traffic Mbps/Gbps
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" v-model="trafficUnit" value="tb">
                                        Traffic TB
                                    </label>
                                </div>
                            </div>
                            <transition name="slide-fade" mode="out-in">
                                <?= $form->field($order, 'traffic_mbps', ['options' => ['v-if' => 'trafficUnit === "mbps"']])
                                    ->dropDownList($order->getItems('traffic_mbps'), ['v-model' => 'order.traffic_mbps'])
                                    ->label(false)
                                    ->hint('{{ showHint("traffic_mbps") }}') ?>
                                <?= $form->field($order, 'traffic_tb', ['options' => ['v-else' => true]])
                                    ->dropDownList($order->getItems('traffic_tb'), ['v-model' => 'order.traffic_tb'])
                                    ->label(false)
                                    ->hint('{{ showHint("traffic_tb") }}') ?>
                            </transition>
                        </div>
                        <transition name="slide-fade" mode="out-in">
                            <div class="text" v-if="trafficUnit === 'mbps'">{{ showPrice('traffic_mbps') }}</div>
                            <div class="text" v-else>{{ showPrice('traffic_tb') }}</div>
                        </transition>
                    </li>
                    <li class="list-group-item">
                        <?= $form->field($order, 'monitoring')->checkbox([
                            ':disabled' => 'isMonitoringDisabled',
                            'v-model' => 'order.monitoring',
                        ]) ?>
                    </li>
                    <li class="list-group-item">
                        <?= $form->field($order, 'ipmi')->dropDownList($order->getItems('ipmi'), [
                            ':disabled' => 'isIPMIDisabled',
                            'v-model' => 'order.ipmi',
                        ]) ?>
                        <div class="text">{{ showPrice('ipmi') }}</div>
                    </li>
                    <transition name="list">
                        <li class="list-group-item" v-if="order.upgrade">
                            <?= $form->field($order, 'licencesOrSupport')->textarea([
                                'rows' => 3,
                                'placeholder' => 'Please note if you need any additional licences (Windows, Cisco, VMware, etc), or additional support (SmartNet)',
                            ]) ?>
                        </li>
                    </transition>
                    <li class="list-group-item">
                        <?= $form->field($order, 'projectInfo')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Please use this space to provide us with short information about the project you want to host. ',
                        ]) ?>
                    </li>
                    <li class="list-group-item">
                        <?= $form->field($order, 'comment')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Please use this space to provide any additional notes or preferences related to your order. ',
                        ]) ?>
                    </li>
                </ul>

            </div>
            <div class="col-md-6">

                <div class="panel panel-default">
                    <div class="panel-heading"><?= $order->getAttributeLabel('location') ?></div>
                    <div class="panel-body">
                        <?= $order->location ?>
                        <?= Html::activeHiddenInput($order, 'location', ['v-model' => 'order.location']) ?>
                    </div>
                    <div class="panel-heading"><?= Yii::t('hipanel.server.irs', 'Final HW Configuration') ?></div>
                    <div class="panel-body">
                        {{ order.config }}
                        <?= Html::activeHiddenInput($order, 'config', ['v-model' => 'order.config']) ?>
                    </div>
                    <?php /**
                    <div class="panel-body" style="border-top: 1px solid #ddd;">
                        <?= $form->field($order, 'upgrade')
                            ->checkbox(['v-model' => 'order.upgrade'])
                            ->hint(
                                Yii::t(
                                    'hipanel.server.irs',
                                    'Please note, that any upgrades and changes may extend delivery time up to 5-7 working days.'
                                )
                            )
                        ?>
                    </div>
                     */ ?>
                </div>

            </div>
            <div class="col-md-6 col-md-6" style="padding-left: 20px">
                <h3 class="text-bold">
                    {{ total }} <?= Yii::t('hipanel.server.irs', 'per month') ?>
                    <?= Html::activeHiddenInput($order, 'price', ['v-model' => 'innerTotal']) ?>
                </h3>
                <p class="text-muted"><?= Yii::t('hipanel.server.irs', 'Free setup') ?></p>
                <p class="text-muted text-sm">
                    <?php // Yii::t('hipanel.server.irs', 'Delivery time: 4h*') ?>
                    <?= Yii::t(
                        'hipanel.server.irs',
                        '*For initial orders placed at this location, there may be a slight delay as we verify customer information and process the order for acceptance.')
                    ?>
                </p>
                <?= Html::submitButton(
                    Yii::t('hipanel.server.irs', 'Order'),
                    [
                        'class' => 'btn btn-success',
                        'style' => ['width' => '13em', 'text-transform' => 'uppercase'],
                        'data-loading-text' => 'Ordering...',
                    ]
                ) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </transition>
</div>
