<?php

use hipanel\modules\server\grid\HubGridView;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\menus\HubDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\MainDetails;
use hipanel\widgets\Pjax;
use hipanel\widgets\SettingsModal;
use yii\helpers\Html;

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .profile-block {
        text-align: center;
    }
');
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
                $box = Box::begin(['renderBody' => false, 'bodyOptions' => ['class' => 'no-padding']]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel:server', 'Switches'));
                $box->endHeader();
                $box->beginBody();
                echo ServerGridView::detailView([
                    'model' => $model,
                    'boxed' => false,
                    'columns' => [
                        'net',
                        'kvm',
                        'location',
                        'pdu',
                        'rack',
                        'ipmi',
                    ],
                ]);
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel:server:hub', 'General information'));
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
                        'login',
                        'ports_num',
                        'traf_server_id',
                        'vlan_server_id',
                        'community',
                        'snmp_version_id',
                        'digit_capacity_id',
                        'nic_media',
                        'base_port_no',
                    ],
                ]);
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
        <div class="row">
            <?php Pjax::begin(['enablePushState' => false]) ?>
            <div class="col-md-12">
                <?php
                    $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                            echo $box->renderTitle(Yii::t('hipanel:server', 'Financial information'));
                        $box->endHeader();
                        $box->beginBody();
                            echo HubGridView::detailView([
                                'boxed'   => false,
                                'model'   => $model,
                                'columns' => [
                                    'tariff',
                                    'sale_time',
                                ],
                            ]);
                        $box->endBody();
                        $box->beginFooter();
                            if (Yii::$app->user->can('hub.sell')) {
                                echo SettingsModal::widget([
                                    'model'    => $model,
                                    'title'    => Yii::t('hipanel:server', 'Change tariff'),
                                    'scenario' => 'sell',
                                    'toggleButton' => [
                                        'class' => 'btn btn-default',
                                    ],
                                ]);
                            }
                        $box->endFooter();
                    $box->end();
                ?>
            </div>
            <?php Pjax::end() ?>
        </div>
        <?= $this->render('@hipanel/modules/server/views/_configuration-view', [
                'model' => $model,
                'configAttrs' => ['units'],
        ]) ?>
    </div>
</div>
