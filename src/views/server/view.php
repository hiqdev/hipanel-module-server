<?php

use hipanel\helpers\Url;
use hipanel\modules\server\assets\ServerTaskCheckerAsset;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\widgets\ChartOptions;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use hipanel\widgets\ClientSellerLink;
use hipanel\widgets\SettingsModal;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * @var $model Server
 */

$this->title = $model->name;
$this->subtitle = Yii::t('hipanel/server', 'Server detailed information') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel/server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

list($chartsLabels, $chartsData) = $model->groupUsesForCharts();

Pjax::begin(Yii::$app->params['pjax']);

?>

<div class="row server-view">
    <div class="col-md-3">
        <?php Box::begin([
            'bodyOptions' => [
                'class' => 'no-padding'
            ]
        ]) ?>
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
                    <li>
                        <?= Html::a('<i class="fa fa-fw fa-forward"></i>' . Yii::t('hipanel/server', 'Renew server'), ['add-to-cart-renewal', 'model_id' => $model->id], ['data-pjax' => 0]) ?>
                    </li>
                <?php if ($model->isPwChangeSupported()) { ?>
                    <li>
                        <?= $this->render('_reset-password', compact(['model'])) ?>
                    </li>
                <?php } ?>
                    <li>
                        <?= Html::a('<i class="fa fa-fw fa-signal"></i>' . Yii::t('hipanel/server', 'Resources usage graphs'), ['@rrd/view', 'id' => $model->id]) ?>
                    </li>
                    <li>
                        <?= Html::a('<i class="fa fa-fw fa-area-chart"></i>' . Yii::t('hipanel/server', 'Switch graphs'), ['@switch-graph/view', 'id' => $model->id]) ?>
                    </li>
                <?php if (Yii::$app->user->can('support')  && Yii::$app->user->not($model->client_id)) : ?>
                    <li>
                        <?= $this->render('_block', compact(['model', 'blockReasons'])) ?>
                    </li>
                <?php endif ?>
                <?php if (Yii::$app->user->can('support')) : ?>
                    <li>
                        <?= $this->render('_delete', compact(['model'])) ?>
                    </li>
                <?php endif ?>
                </ul>
            </div>
            <?php Pjax::end() ?>
        <?php Box::end() ?>

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
                $box->beginBody() ?>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <?= $this->render('_reboot', compact(['model'])) ?>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <?= $this->render('_shutdown', compact(['model'])) ?>
                    </div>
                    <?php if ($model->isLiveCDSupported()) : ?>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->render('_boot-live', compact(['model', 'osimageslivecd'])) ?>
                        </div>
                    <?php endif ?>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <?= $this->render('_reinstall', compact(['model', 'groupedOsimages', 'panels'])) ?>
                    </div>
                </div>
                    <?php
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
                    $box->beginBody(); ?>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <?= $this->render('_power-on', compact(['model'])) ?>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <?= $this->render('_power-off', compact(['model'])) ?>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <?= $this->render('_reset', compact(['model'])) ?>
                            </div>
                        </div>

                    <?php $box->endBody(); $box->end(); ?>
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
                                'client_id', 'seller_id',
                                [
                                    'attribute' => 'name',
                                    'contentOptions' => ['class' => 'text-bold'],
                                ],
                                'ip', 'note', 'label',
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
                        if (Yii::$app->user->can('manage')) {
                            echo SettingsModal::widget([
                                'model'    => $model,
                                'title'    => Yii::t('hipanel/server', 'Change tariff'),
                                'scenario' => 'sale',
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
    </div>
    <div class="col-md-5">
        <?php echo $this->render('_ip', ['model' => $model]) ?>
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
$this->registerCss('
th {
    white-space: nowrap;
}

.btn-block {
    margin-bottom: .5em
}');

if ($model->running_task) {
    ServerTaskCheckerAsset::register($this);

    $checkerOptions = Json::encode([
        'id' => $model->id,
        'ajax' => ['url' => Url::to('@server/is-operable')],
        'pjaxSelector' => '#' . Yii::$app->params['pjax']['id']
    ]);

    $this->registerJs("$('.server-view').serverTaskChecker($checkerOptions);");
}

Pjax::end();
