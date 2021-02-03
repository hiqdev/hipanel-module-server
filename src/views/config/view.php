<?php

use hipanel\modules\server\grid\ConfigGridView;
use hipanel\modules\server\models\Config;
use hipanel\modules\server\menus\ConfigDetailMenu;
use hipanel\modules\stock\widgets\HardwareSettingsDetail;
use hipanel\widgets\Box;
use hipanel\widgets\ClientSellerLink;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;
use yii\web\View;


/**
 * @var View $this
 * @var Config $model
 */
$this->title = Html::encode($model->name);
$this->params['subtitle'] = Html::encode($model->label);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server:config', 'Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$linkTemplate = '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>';
?>
<div class="row">
    <div class="col-md-3">
        <?= MainDetails::widget([
            'title' => 'Configuration',
            'icon' => 'fa-cogs',
            'subTitle' => ClientSellerLink::widget(['model' => $model]),
            'menu' => ConfigDetailMenu::widget(['model' => $model], [
                'linkTemplate' => $linkTemplate,
            ]),
        ]) ?>
        <?= HardwareSettingsDetail::widget(['id' => $model->id, 'type' => 'config']) ?>
    </div>

    <div class="col-md-5">
        <?php
        $box = Box::begin(['renderBody' => false]);
            $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel:server', 'Config information'));
            $box->endHeader();
            $box->beginBody();
                echo ConfigGridView::detailView([
                    'boxed'   => false,
                    'model'   => $model,
                    'columns' => [
                        'client', 'seller', 'name', 'descr',
                        'nl_tariff', 'us_tariff',
                        'nl_servers', 'us_servers',
                        'nl_low_limit', 'us_low_limit',
                        'servers', 'profiles', 'state', 'sort_order',
                    ],
                ]);
            $box->endBody();
        $box->end();
        ?>
    </div>
    <div class="col-md-4">
        <?php
        $box = Box::begin(['renderBody' => false]);
            $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel:server', 'Hardware'));
            $box->endHeader();
            $box->beginBody();
                echo ConfigGridView::detailView([
                    'boxed'   => false,
                    'model'   => $model,
                    'columns' => [
                        'cpu', 'ram', 'hdd', 'ssd',
                        'traffic', 'lan', 'raid',
                    ],
                ]);
            $box->endBody();
        $box->end();
        ?>
    </div>
</div>
