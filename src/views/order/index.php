<?php

use hipanel\modules\server\assets\ServerAsset;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:server:order', 'Order server');
$this->params['breadcrumbs'][] = $this->title;

ServerAsset::register($this);

$this->registerJs("
function eq() {
    var openvz = $('#openvz .order-body'), xenssd = $('#xenssd .order-body');
    openvz.height(xenssd.height());
}
$( window ).on('resize', function() { eq(); });
$('a.sidebar-toggle').click(function() { setTimeout(function(){ eq(); }, 500); });
eq();
", \yii\web\View::POS_LOAD);

?>
<style>
    .order-box {
        padding: 10px;
        background: #fff;
        border-radius: 3px;
    }

    .order-header {
        border-bottom: 1px solid #f4f4f4;
        padding-bottom: 10px;
    }

    .order-title {
        font-size: 18px;
        margin: 10px;
        line-height: 1;
    }

    .order-body {
        padding: 1em;
        height: auto;
    }
</style>


<div class="row">
    <div class="col-sm-6">
        <div id="openvz" class="order-box">
            <div class="order-header text-center">
                <h3 class="order-title">
                    <?= Html::img(Yii::$app->assetManager->getPublishedUrl('@vendor/hiqdev/hipanel-module-server/src/assets') . '/img/openvz-logo.png', ['style' => 'height: 15px; line-height: 1;']) ?>
                    &nbsp;
                    <?= Yii::t('hipanel:server:order', 'OpenVZ') ?>
                </h3>
            </div>
            <div class="order-body">
                <p class="text-muted text-justify">
                    <?= Yii::t('hipanel:server:order', 'VDS based on OpenVZ - is an inexpensive and reliable solution for small projects that do not require many resources (HTML web-sites, landing pages, small blogs, personal websites, business cards, etc.). An additional advantage of our VDS based on OpenVZ is utilization of SSD cache system that improves performance of the disk subsystem during frequently accessed data readings.') ?>
                </p>
            </div>
            <?= Html::a(Yii::t('hipanel:server:order', 'ORDER SERVER'), ['open-vz'], ['class' => 'btn bg-orange btn-lg btn-block']) ?>
        </div>
    </div>
    <div id="xenssd" class="col-sm-6">
        <div class="order-box">
            <div class="order-header text-center">
                <h3 class="order-title">
                    <?= Html::img(Yii::$app->assetManager->getPublishedUrl('@vendor/hiqdev/hipanel-module-server/src/assets') . '/img/xen-logo.png', ['style' => 'height: 15px; line-height: 1;']) ?>
                    &nbsp;
                    <?= Yii::t('hipanel:server:order', 'XEN SSD') ?>
                </h3>
            </div>
            <div class="order-body">
                <p class="text-muted text-justify">
                    <?= Yii::t('hipanel:server:order', 'The main advantage of a VDS based on XEN with SSD is speed. It is more than 250 times faster than a conventional HDD. Due to Xen virtualization type, all resources are assigned to user and the operation of your VDS does not depend on the main server\'s load.') ?>
                    <?= Yii::t('hipanel:server:order', 'Virtual dedicated server based on Xen is a perfect solution for most medium and large projects because of its performance that is highly competitive with the performance of a dedicated server.') ?>
                </p>
            </div>
            <?= Html::a(Yii::t('hipanel:server:order', 'ORDER SERVER'), ['xen-ssd'], ['class' => 'btn bg-purple btn-lg btn-block']) ?>
        </div>
    </div>
</div>

