<?php

use hipanel\modules\server\assets\OsSelectionAsset;
use hipanel\modules\server\models\Package;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * @var Package $package
 * @var \hipanel\modules\server\cart\ServerOrderProduct $product
 * @var array $groupedOsimages
 * @var array $panels
 */

$this->title = Yii::t('hipanel/server/order', 'Order creating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel/server/order', 'Buy server'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

OsSelectionAsset::register($this);
$this->registerCss("
.order-price {
    color: #444;
    font-size: 47px;
    margin-top: 15px;
    margin-bottom: 25px;
}
");
?>
    <div class="row">
        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <?= $package->getName() ?>
                </div>
                <div class="box-body">
                    <div class="text-center order-price">
                        <?= Yii::t('hipanel/server/order', '{price}/mo', ['price' => Yii::$app->formatter->asCurrency($package->getPrice(), Yii::$app->params['currency'])]) ?>
                    </div>

                    <dl class="dl-horizontal">
                        <?php foreach (['cpu', 'ram', 'hdd', 'ip', 'traffic'] as $item) : ?>
                            <dt><?= $package->getResourceTitle($item) ?></dt>
                            <dd><?= $package->getResourceValue($item) ?></dd>
                        <?php endforeach ?>
                        <dt><?= Yii::t('hipanel/server/order', 'Traffic overuse') ?></dt>
                        <dd><?= Yii::t('hipanel/server/order', '{price}/{unit}', $package->getOverusePrice('traffic')) ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('hipanel/server/order', 'Customise your server'); // todo: we need a good text  ?></h3>
                </div>
                <div class="box-body">
                    <div class="order-boxes">


                        <?php $form = ActiveForm::begin(['action' => ['add-to-cart']]) ?>
                        <?= Html::activeHiddenInput($product, 'tariff_id', ['name' => 'tariff_id']) ?>
                        <?= Html::hiddenInput('osimage', null, ['class' => 'reinstall-osimage']) ?>
                        <?= Html::hiddenInput('panel', null, ['class' => 'reinstall-panel']) ?>

                        <div class="os-selector md-mt-30">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="list-group">
                                        <div class="list-group-item disabled">
                                            <h4 class="list-group-item-heading"><?= Yii::t('hipanel/server/order', 'Location') ?></h4>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="list-group-item-text os-list">
                                                <?= $form->field($product, 'cluster_id')->dropDownList($package->getLocations(), ['name' => 'cluster_id'])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="list-group">
                                        <div class="list-group-item disabled">
                                            <h4 class="list-group-item-heading"><?= Yii::t('hipanel/server/order', 'OS') ?></h4>
                                        </div>
                                        <?php
                                        foreach ($groupedOsimages['vendors'] as $vendor) { ?>
                                            <div class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $vendor['name'] ?></h4>
                                                <div class="list-group-item-text os-list">
                                                    <?php foreach ($vendor['oses'] as $system => $os) {
                                                        echo Html::tag('div', Html::radio('os', false, [
                                                            'label' => $os,
                                                            'value' => $system,
                                                            'class' => 'radio',
                                                        ]), ['class' => 'radio']);
                                                    } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="list-group">
                                        <div class="list-group-item disabled">
                                            <h4 class="list-group-item-heading"><?= Yii::t('hipanel/server/order', 'Panel and software') ?></h4>
                                        </div>
                                        <?php foreach ($panels as $panel => $panel_name) {
                                            if (empty($groupedOsimages['softpacks'][$panel])) {
                                                continue;
                                            } ?>

                                            <div class="list-group-item soft-list" data-panel="<?= $panel ?>">
                                                <h4 class="list-group-item-heading"><?= Yii::t('hipanel/server/panel', $panel_name) ?></h4>

                                                <div class="list-group-item-text">
                                                    <?php foreach ($groupedOsimages['softpacks'][$panel] as $softpack) { ?>
                                                        <div class="radio">
                                                            <label>
                                                                <?= Html::radio('panel_soft', false, [
                                                                    'data' => [
                                                                        'panel-soft' => 'soft',
                                                                        'panel' => $panel
                                                                    ],
                                                                    'value' => $softpack['name'],
                                                                ]) ?>
                                                                <strong><?= Yii::t('hipanel/server/panel', $softpack['name']) ?></strong>
                                                                <small style="font-weight: normal">
                                                                    <?= Yii::t('hipanel/server/os', $softpack['description']) ?>
                                                                </small>
                                                                <a class="softinfo-bttn glyphicon glyphicon-info-sign"
                                                                   href="#"></a>

                                                                <div class="soft-desc" style="display: none;"></div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="list-group">
                                        <div class="list-group-item disabled">
                                            <h4 class="list-group-item-heading"><?= Yii::t('hipanel/server/order', 'Information') ?></h4>
                                        </div>
                                        <div class="list-group-item">
                                            <?= $form->field($product, 'purpose')->textInput(['name' => 'purpose']) ?>
                                        </div>
                                        <div class="list-group-item">
                                            <?= $form->field($product, 'social')->textInput(['name' => 'social']) ?>
                                        </div>
                                        <div class="list-group-item">
                                            <?= Html::submitButton(Yii::t('cart', 'Place order'), ['class' => 'btn btn-default btn-block']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->registerJs("
    var osparams = " . Json::encode($groupedOsimages['oses']) . ";
    $('.os-selector').osSelector({
        osparams: osparams
    });
", \yii\web\View::POS_READY, 'os-selector-init');

