<?php

use hipanel\grid\XEditableColumn;
use hipanel\helpers\Url;
use hipanel\modules\server\assets\OsSelectionAsset;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\widgets\ChartOptions;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use hipanel\widgets\ClientSellerLink;
use yii\helpers\Html;

/**
 * @var $model Server
 */

$this->title    = $model->name;
$this->subtitle = Yii::t('hipanel/server', 'Server detailed information') . ' #' . $model->id;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('hipanel/server', 'Servers'), 'url' => ['index']],
    $this->title,
]);

list($chartsLabels, $chartsData) = $model->groupUsesForCharts();

Pjax::begin();

?>

<div class="row">
    <div class="col-md-3">
        <?php Box::begin(); ?>
            <div class="profile-user-img text-center">
                <i class="fa fa-server fa-5x"></i>
            </div>
            <p class="text-center">
                <span class="profile-user-role"><?= $model->name ?></span>
                <br>
                <span class="profile-user-name"><?= ClientSellerLink::widget(compact('model')) ?></span>
            </p>
            <?php Pjax::begin(['enablePushState' => false]) ?>
            <div class="profile-usermenu">
                <ul class="nav">
                    <?php if ($model->isPwChangeSupported()) { ?>
                        <li>
                            <?= $this->render('_reset-password', compact(['model'])) ?>
                        </li>
                    <?php } ?>
                    <li>
                        <?= $this->render('_reinstall', compact(['model', 'grouped_osimages', 'panels'])) ?>
                    </li>
                    <?php if (Yii::$app->user->can('support')  && Yii::$app->user->id != $model->client_id) { ?>
                        <li>
                            <?= $this->render('_block', compact(['model', 'blockReasons'])); ?>
                        </li>
                    <?php } ?>
                    <?php if (Yii::$app->user->can('support')) { ?>
                        <li>
                            <?= $this->render('_delete', compact(['model'])) ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php Pjax::end() ?>
        <?php Box::end(); ?>

        <?php if ($model->isVNCSupported()) { ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                            echo $box->renderTitle(Yii::t('hipanel/server', 'VNC server'));
                        $box->endHeader();
                        $box->beginBody();
                            echo $this->render('_vnc', compact(['model']));
                        $box->endBody();
                    $box->end();
                    ?>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                    echo $box->renderTitle(Yii::t('hipanel/server', 'System management'));
                $box->endHeader();
                $box->beginBody();
                    echo $this->render('_reboot', compact(['model']));
                    echo $this->render('_shutdown', compact(['model']));
                    if ($model->isLiveCDSupported()) {
                        echo $this->render('_boot-live', compact(['model', 'osimageslivecd']));
                    }
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                        echo $box->renderTitle(Yii::t('hipanel/server', 'Power management'));
                    $box->endHeader();
                    $box->beginBody();
                        echo $this->render('_power-on', compact(['model']));
                        echo $this->render('_power-off', compact(['model']));
                        echo $this->render('_reset', compact(['model']));
                    $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                        echo $box->renderTitle(Yii::t('hipanel/server', 'Event log'));
                    $box->endHeader();
                    $box->beginBody();
                        echo $this->render('_log', compact('model'));
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
                        echo $box->renderTitle(Yii::t('hipanel/server', 'Server information'));
                    $box->endHeader();
                    $box->beginBody();
                        echo ServerGridView::detailView([
                            'boxed'   => false,
                            'model'   => $model,
                            'gridOptions' => [
                                'osImages' => $osimages,
                            ],
                            'columns' => [
                                'client_id', 'seller_id', 'note', 'label',
                                ['attribute' => 'name'],
                                'state', 'os', 'panel'
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
                        echo $box->renderTitle(Yii::t('hipanel/server', 'Financial information'));
                    $box->endHeader();
                    $box->beginBody();
                        echo ServerGridView::detailView([
                            'boxed'   => false,
                            'model'   => $model,
                            'columns' => [
                                'tariff', 'tariff_note', 'sale_time', 'discount', 'expires',
                            ],
                        ]);
                    $box->endBody();
                    $box->beginFooter();
                        echo $this->render('_refuse', compact(['model']));
                    $box->endFooter();
                $box->end();
                ?>
            </div>
            <?php Pjax::end() ?>
        </div>
        <?php if (Yii::getAlias('@part', false) && Yii::$app->user->can('support')) { ?>
            <div class="row">
                <?php Pjax::begin(['enablePushState' => false]) ?>
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                            echo $box->renderTitle(Yii::t('hipanel/server', 'Parts'));
                        $box->endHeader();
                        $box->beginBody();
                            $url = Url::to(['@part/render-object-parts', 'id' => $model->id]);
                            echo Html::tag('div', '', ['class'  => 'server-parts']);
                            $this->registerJs("$('.server-parts').load('$url', function () {
                                $(this).closest('.box').find('.overlay').remove();
                            });");
                        $box->endBody(); ?>
                            <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                        <?php
                    $box->end();
                    ?>
                </div>
                <?php Pjax::end() ?>
            </div>
        <?php } ?>
        <?php if (Yii::getAlias('@ip', false)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $ipsDataProvider = new \yii\data\ArrayDataProvider([
                        'allModels' => $model->ips,
                        'pagination' => false,
                        'sort' => false,
                    ]);

                    $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                            echo $box->renderTitle(Yii::t('hipanel/server', 'IP addresses'));
                        $box->endHeader();
                        $box->beginBody();
                            echo \hipanel\grid\GridView::widget([
                                'dataProvider' => $ipsDataProvider,
                                'columns' => [
                                    [
                                        'attribute' => 'ip',
                                        'format' => 'html',
                                        'label' => Yii::t('hipanel', 'IP address'),
                                        'options' => [
                                            'style' => 'width: 20%',
                                        ],
                                        'value' => function ($model) {
                                            if (Yii::$app->user->can('support') && Yii::getAlias('@ip', false) && $model->id) {
                                                return Html::a($model->ip, ['@ip/view', 'id' => $model->id]);
                                            }

                                            return $model->ip;
                                        }
                                    ],
                                    [
                                        'label' => Yii::t('hipanel', 'PTR'),
                                        'attribute' => 'ptr',
                                        'options' => [
                                            'style' => 'width: 40%',
                                        ],
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->canSetPtr()) {
                                                return \hipanel\widgets\XEditable::widget([
                                                    'model' => $model,
                                                    'attribute' => 'ptr',
                                                    'pluginOptions' => [
                                                        'url'       => Url::to('@ip/set-ptr')
                                                    ]
                                                ]);
                                            }

                                            return null;
                                        }
                                    ],
                                    [
                                        'attribute' => 'links',
                                        'format' => 'html',
                                        'label' => Yii::t('hipanel', 'Services'),
                                        'value' => function ($model) {
                                            return \hipanel\widgets\ArraySpoiler::widget([
                                                'data' => $model->links,
                                                'formatter' => function ($link) {
                                                    if (Yii::$app->user->can('support') && Yii::getAlias('@service', false)) {
                                                        return Html::a($link->service, ['@service/view', 'id' => $link->service_id]);
                                                    }

                                                    return $link->service;
                                                }
                                            ]);
                                        }
                                    ]
                                ]
                            ]);
                        $box->endBody();
                        if (Yii::$app->user->can('support')) {
                            $box->beginFooter();
                            echo Html::a(
                                Yii::t('hipanel/server', 'Manage IP addresses'),
                                ['@ip', 'IpSearch' => ['server_in' => $model->name]],
                                ['class' => 'btn btn-default btn-sm']
                            );
                            $box->endFooter();
                        }
                    $box->end();
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-5">
        <?php if (isset($chartsData['server_traf'])) { ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                            echo $box->renderTitle(Yii::t('hipanel/server', 'Traffic consumption'));
                            $box->beginTools();
                                echo ChartOptions::widget([
                                    'id' => 'traffic-consumption',
                                    'form' => [
                                        'action' => 'draw-chart'
                                    ],
                                    'hiddenInputs' => [
                                        'id' => ['value' => $model->id],
                                        'type' => ['value' => 'traffic']
                                    ]
                                ]);
                            $box->endTools();
                        $box->endHeader();
                        $box->beginBody();
                            echo $this->render('_traffic_consumption', ['labels' => $chartsLabels, 'data' => $chartsData]);
                        $box->endBody();
                    $box->end();
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php if (isset($chartsData['server_traf95'])) { ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                            echo $box->renderTitle(Yii::t('hipanel/server', 'Bandwidth consumption'));
                            $box->beginTools();
                            echo ChartOptions::widget([
                                'id' => 'bandwidth-consumption',
                                'form' => [
                                    'action' => 'draw-chart'
                                ],
                                'hiddenInputs' => [
                                    'id' => ['value' => $model->id],
                                    'type' => ['value' => 'bandwidth']
                                ]
                            ]);
                            $box->endTools();
                        $box->endHeader();
                        $box->beginBody();
                            echo $this->render('_bandwidth_consumption', ['labels' => $chartsLabels, 'data' => $chartsData]);
                        $box->endBody();
                    $box->end();
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
$this->registerCss("th { white-space: nowrap; }");
Pjax::end();
