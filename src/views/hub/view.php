<?php

use hipanel\modules\server\grid\HubGridView;
use hipanel\modules\server\menus\HubDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss("
    .profile-block {
        text-align: center;
    }
");
?>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <?= MainDetails::widget([
                    'title' => $model->name,
                    'icon' => 'fa-arrows-alt',
                    'subTitle' => Html::a($model->buyer, ['@client/view', 'id' => $model->buyer_id]),
                    'menu' => HubDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
                ]) ?>
            </div>
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel:server', 'Switches'));
                $box->endHeader();
                $box->beginBody();
                //                echo ServerGridView::detailView([
                //                    'model' => $model,
                //                    'boxed' => false,
                //                    'columns' => [
                //                        'net',
                //                        'pdu',
                //                        'rack',
                //                        'ipmi',
                //                    ]
                //                ]);
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel:server:hub', 'General inforamtion'));
                $box->endHeader();
                $box->beginBody();
                echo HubGridView::detailView([
                    'model' => $model,
                    'boxed' => false,
                    'columns' => [
                        'switch',
                        'inn',
                        'buyer',
                        'model',
                        'type',
                        'ip',
                        'mac',
                    ],
                ]);
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
    </div>
</div>
