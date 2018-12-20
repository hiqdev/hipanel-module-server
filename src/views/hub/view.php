<?php

use hipanel\modules\server\grid\HubGridView;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\menus\HubDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;
use yii\widgets\Pjax;
use hipanel\helpers\Url;
use yii\widgets\DetailView;

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
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel:server', 'Switches'));
                $box->endHeader();
                $box->beginBody();
                echo ServerGridView::detailView([
                    'model' => $model,
                    'boxed' => false,
                    'columns' => [
                        'net',
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
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
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

            <?php if (Yii::getAlias('@part', false) && Yii::$app->user->can('part.read')) : ?>
                <div class="row">
                    <?php Pjax::begin(['enablePushState' => false]) ?>
                    <div class="col-md-6">
                        <?php $box = Box::begin(['renderBody' => false]) ?>
                        <?php $box->beginHeader() ?>
                        <?= $box->renderTitle(Yii::t('hipanel:server', 'Configuration')) ?>
                        <?php $box->beginTools(['class' => 'box-tools pull-right']) ?>
                        <?= Html::a(
                            Yii::t('hipanel', 'Details'),
                            Url::toSearch('part', ['dst_name_like' => $model->name]),
                            ['class' => 'btn btn-box-tool']
                        ) ?>
                        <?php $box->endTools() ?>
                        <?php $box->endHeader() ?>
                        <?php $box->beginBody() ?>
                        <?= DetailView::widget([
                            'model' => $model->hardwareSettings,
                            'attributes' => ['units'],
                        ]) ?>
                        <hr>
                        <?php $url = Url::to(['@part/render-object-parts', 'id' => $model->id]) ?>
                        <?= Html::tag('div', '', ['class'  => 'server-parts']) ?>
                        <?php $this->registerJs("$('.server-parts').load('$url', function () {
                                $(this).closest('.box').find('.overlay').remove();
                            });") ?>
                        <?php $box->endBody() ?>
                        <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                        <?php $box->end() ?>
                    </div>
                    <?php Pjax::end() ?>
                </div>
            <?php endif ?>
    </div>
</div>
