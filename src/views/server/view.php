<?php

use hipanel\helpers\Url;
use hipanel\modules\finance\grid\HistorySalesGridView;
use hipanel\modules\finance\models\Consumption;
use hipanel\modules\finance\widgets\ConsumptionViewer;
use hipanel\modules\server\assets\ServerTaskCheckerAsset;
use hipanel\modules\server\grid\BindingColumn;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\menus\ServerDetailMenu;
use hipanel\modules\server\models\Binding;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\widgets\BootLive;
use hipanel\modules\server\widgets\ChartOptions;
use hipanel\modules\server\widgets\Configuration;
use hipanel\modules\server\widgets\PowerChartOptions;
use hipanel\modules\server\widgets\ServerSwitcher;
use hipanel\modules\server\widgets\Wizzard;
use hipanel\widgets\Box;
use hipanel\widgets\ClientSellerLink;
use hipanel\widgets\EventLog;
use hipanel\widgets\Pjax;
use hipanel\widgets\SettingsModal;
use hipanel\widgets\SimpleOperation;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var Server $model
 * @var array $blockReasons
 * @var array $osimageslivecd
 * @var array $groupedOsimages
 * @var array $panels
 * @var array $osimages
 * @var Consumption $consumption
 */

$this->title = $model->name;
$this->params['subtitle'] = Yii::t('hipanel:server', 'Server detailed information') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

[$chartsLabels, $chartsData] = $model->groupUsesForCharts();

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
        'pjaxSelector' => '#' . Yii::$app->params['pjax']['id'],
    ]);
    $this->registerJs("$('.server-view').serverTaskChecker($checkerOptions);");
}

?>

<div class="row server-view">
    <div class="col-md-3">
        <?= ServerSwitcher::widget(['model' => $model]) ?>

        <?php Box::begin([
            'bodyOptions' => [
                'class' => 'no-padding',
            ],
        ]) ?>
        <div class="profile-user-img text-center">
            <i class="fa fa-server fa-5x"></i>
        </div>
        <p class="text-center">
            <span class="profile-user-role"><?= $model->name ?></span>
            <br>
            <span class="profile-user-name"><?= ClientSellerLink::widget(['model' => $model]) ?></span>
        </p>
        <?php Pjax::begin(['enablePushState' => false]) ?>
        <div class="profile-usermenu">
            <?= ServerDetailMenu::widget(['model' => $model, 'blockReasons' => $blockReasons]) ?>
        </div>
        <?php Pjax::end() ?>
        <?php Box::end() ?>

        <?php if ($model->isVNCSupported() && Yii::$app->user->can('server.control-system')) : ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                    echo $box->renderTitle(Yii::t('hipanel:server', 'VNC server'));
                    Box::endHeader();
                    $box->beginBody();
                    echo $this->render('_vnc', compact(['model']));
                    $box->endBody();
                    Box::end();
                    ?>
                </div>
            </div>
        <?php endif ?>
        <?php if ($model->canControlPower()) : ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                    echo $box->renderTitle(Yii::t('hipanel:server', 'System management'));
                    Box::endHeader();
                    $box->beginBody() ?>
                    <div class="row">
                        <?php if (Yii::$app->user->can('server.control-power')) : ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <?= SimpleOperation::widget([
                                    'model' => $model,
                                    'scenario' => 'reboot',
                                    'buttonLabel' => Yii::t('hipanel:server', 'Reboot'),
                                    'body' => '<div class="callout callout-warning">
<h4>' . Yii::t('hipanel:server', 'This may cause data loose!') . '</h4>
</div>
<p>' . Yii::t('hipanel:server',
                                            'Reboot will interrupt all processes on the server. Are you sure you want to reset the server?') . '</p>',
                                    'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm server reboot'),
                                    'modalHeaderOptions' => ['class' => 'label-warning'],
                                    'modalFooterLabel' => Yii::t('hipanel:server', 'Reboot'),
                                    'modalFooterLoading' => Yii::t('hipanel:server', 'Rebooting...'),
                                    'modalFooterClass' => 'btn btn-warning',
                                ]) ?>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <?= SimpleOperation::widget([
                                    'model' => $model,
                                    'scenario' => 'shutdown',
                                    'buttonLabel' => Yii::t('hipanel:server', 'Shutdown'),
                                    'body' => '<div class="callout callout-warning">
<h4>' . Yii::t('hipanel:server', 'This may cause data loose!') . '</h4>
</div>
<p>' . Yii::t('hipanel:server',
                                            'Shutdown will interrupt all processes on the server. Are you sure you want to shutdown the server?') . '</p>',
                                    'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm server shutdown'),
                                    'modalHeaderOptions' => ['class' => 'label-warning'],
                                    'modalFooterLabel' => Yii::t('hipanel:server', 'Shutdown'),
                                    'modalFooterLoading' => Yii::t('hipanel:server', 'Shutting down...'),
                                    'modalFooterClass' => 'btn btn-warning',
                                ]) ?>
                            </div>
                        <?php endif ?>
                        <?php if ($model->isLiveCDSupported() && Yii::$app->user->can('server.control-system')) : ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <?= BootLive::widget([
                                    'model' => $model,
                                    'osimageslivecd' => $osimageslivecd,
                                ]) ?>
                            </div>
                        <?php endif ?>
                        <?php if ($model->isVirtualDevice() && Yii::$app->user->can('server.control-system')) : ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <?= $this->render('_reinstall', compact(['model', 'groupedOsimages', 'panels'])) ?>
                            </div>
                        <?php endif ?>
                        <?php if (Yii::$app->user->can('server.wizzard')) : ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <?= Wizzard::widget(compact(['model'])) ?>
                            </div>
                        <?php endif ?>
                    </div>
                    <?php
                    $box->endBody();
                    Box::end();
                    ?>
                </div>
            </div>
        <?php endif ?>
        <?php if ($model->isVirtualDevice() && Yii::$app->user->can('server.control-power')) : ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                    echo $box->renderTitle(Yii::t('hipanel:server', 'Power management'));
                    Box::endHeader();
                    $box->beginBody(); ?>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <?= SimpleOperation::widget([
                                'model' => $model,
                                'scenario' => 'power-on',
                                'buttonLabel' => Yii::t('hipanel:server', 'Power on'),
                                'body' => Yii::t('hipanel:server', 'Turn ON server power?'),
                                'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm server power ON'),
                                'modalHeaderOptions' => ['class' => 'label-info'],
                                'modalFooterLabel' => Yii::t('hipanel:server', 'Power ON'),
                                'modalFooterLoading' => Yii::t('hipanel:server', 'Turning power ON...'),
                                'modalFooterClass' => 'btn btn-info',
                            ]) ?>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <?= SimpleOperation::widget([
                                'model' => $model,
                                'scenario' => 'power-off',
                                'buttonLabel' => Yii::t('hipanel:server', 'Power off'),
                                'body' => '<div class="callout callout-warning">
<h4>' . Yii::t('hipanel:server', 'This may cause data loose!') . '</h4>
</div>
<p>' . Yii::t('hipanel:server',
                                        'Power off will immediately interrupt all processes on the server in a dangerous way. Always try to shutdown it, before turning off the power. Are you sure you want to power off the server?') . '</p>',
                                'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm server shutdown'),
                                'modalHeaderOptions' => ['class' => 'label-warning'],
                                'modalFooterLabel' => Yii::t('hipanel:server', 'Power OFF'),
                                'modalFooterLoading' => Yii::t('hipanel:server', 'Turning power OFF...'),
                                'modalFooterClass' => 'btn btn-warning',
                            ]) ?>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <?= SimpleOperation::widget([
                                'model' => $model,
                                'scenario' => 'reset',
                                'buttonLabel' => Yii::t('hipanel:server', 'Reset'),
                                'body' => '<div class="callout callout-warning">
<h4>' . Yii::t('hipanel:server', 'This may cause data loose!') . '</h4>
</div>
<p>' . Yii::t('hipanel:server',
                                        'Power reset will interrupt all processes on the server in a dangerous way. Always try to reboot it, before resetting. Are you sure you want to reset power of the server?') . '</p>',
                                'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm server power reset'),
                                'modalHeaderOptions' => ['class' => 'label-warning'],
                                'modalFooterLabel' => Yii::t('hipanel:server', 'Reset power'),
                                'modalFooterLoading' => Yii::t('hipanel:server', 'Resetting power...'),
                                'modalFooterClass' => 'btn btn-warning',
                            ]) ?>
                        </div>
                    </div>
                    <?php $box->endBody();
                    Box::end(); ?>
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel:server', 'Server information'));
                Box::endHeader();
                $box->beginBody();
                echo ServerGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'gridOptions' => [
                        'osImages' => $osimages,
                    ],
                    'columns' => [
                        'client_id',
                        'seller_id',
                        [
                            'attribute' => 'name',
                            'contentOptions' => ['class' => 'text-bold'],
                        ],
                        'detailed_type',
                        'state',
                        'ip',
                        'note',
                        'label',
                        'blocking',
                        'mails_num',
                        'tags'
                    ],
                ]);
                if (!empty($model->softwareSettings->failure_contacts)) {
                    echo '<hr>';
                    echo DetailView::widget([
                        'model' => $model->softwareSettings,
                        'attributes' => ['failure_contacts'],
                    ]);
                }
                $box->endBody();
                Box::end();
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
                Box::endHeader();
                $box->beginBody();
                echo HistorySalesGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'finished_sales',
                        'active_sales',
                        'future_sales',
                    ],
                ]);
                $box->endBody();
                $box->beginFooter();
                if ($model->autorenewal && $model->expires && Yii::$app->user->can('DISABLED:server.pay')) {
                    echo SimpleOperation::widget([
                        'model' => $model,
                        'scenario' => 'refuse',
                        'buttonLabel' => Yii::t('hipanel:server', 'Refuse service'),
                        'buttonClass' => 'btn btn-default',
                        'body' => function ($model) {
                            return $model->canFullRefuse()
                                ? Yii::t('hipanel:server',
                                    'In case of service refusing, the server will be locked and turned off. All data on the server will be removed!')
                                : Yii::t('hipanel:server',
                                    'In case of service refusing, the server will be locked and turned off {0, date, medium}. All data on the server will be removed!',
                                    Yii::$app->formatter->asTimestamp($model->expires));
                        },
                        'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm service refuse'),
                        'modalHeaderOptions' => ['class' => 'label-danger'],
                        'modalFooterLabel' => Yii::t('hipanel:server', 'Refuse'),
                        'modalFooterLoading' => Yii::t('hipanel:server', 'Refusing...'),
                        'modalFooterClass' => 'btn btn-warning',
                    ]);
                } elseif (in_array($model->state,
                        $model->goodStates(),
                        true) && $model->expires && Yii::$app->user->can('server.pay') && Yii::$app->params['module.server.renew.allowed']) {
                    echo SimpleOperation::widget([
                        'model' => $model,
                        'scenario' => 'enable-autorenewal',
                        'buttonLabel' => Yii::t('hipanel:server', 'Renew service'),
                        'buttonClass' => 'btn btn-default',
                        'body' => function ($model) {
                            return Yii::t('hipanel:server', 'Are you sure, you want to renew the service?');
                        },
                        'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm service renewal'),
                        'modalHeaderOptions' => ['class' => 'label-info'],
                        'modalFooterLabel' => Yii::t('hipanel:server', 'Renew'),
                        'modalFooterLoading' => Yii::t('hipanel:server', 'Renewing...'),
                        'modalFooterClass' => 'btn btn-info',
                    ]);
                }
                if (Yii::$app->user->can('server.sell')) {
                    echo SettingsModal::widget([
                        'size' => Modal::SIZE_LARGE,
                        'model' => $model,
                        'title' => Yii::t('hipanel:server', 'Change tariff'),
                        'scenario' => 'bulk-sale',
                        'toggleButton' => [
                            'class' => 'btn btn-default',
                        ],
                    ]);
                }
                $box->endFooter();
                Box::end();
                ?>

            </div>
            <?php Pjax::end() ?>
        </div>
    </div>
    <div class="col-md-5">
        <?= $this->render('_ip', ['model' => $model]) ?>
        <?php if (Yii::$app->user->can('hub.read') && !empty($model->bindings)) : ?>
            <div class="row">
                <div class="col-md-12">
                    <?php $box = Box::begin(['renderBody' => false]) ?>
                    <?php $box->beginHeader() ?>
                    <?= $box->renderTitle(Yii::t('hipanel:server', 'Switches')) ?>
                    <?php if (Yii::$app->user->can('server.update')) : ?>
                        <?php $box->beginTools(['class' => 'box-tools pull-right']) ?>
                        <?= Html::a(
                            Yii::t('hipanel:server', 'Assign hubs'),
                            ['@server/assign-hubs', 'id' => $model->id],
                            ['class' => 'btn btn-box-tool']
                        ) ?>
                        <?php $box->endTools() ?>
                    <?php endif ?>
                    <?php Box::endHeader() ?>
                    <?php $box->beginBody() ?>
                    <div class="table-responsive">
                        <?= ServerGridView::detailView([
                            'model' => $model,
                            'boxed' => false,
                            'columns' => array_map(function ($binding) use ($model) {
                                /** @var Binding $binding */
                                return [
                                    'class' => BindingColumn::class,
                                    'attribute' => $binding->typeWithNo,
                                    'serverName' => $model->name,
                                    'deviceId' => $binding->obj_id,
                                ];
                            }, $model->bindings),
                        ]) ?>
                    </div>
                    <?php $box->endBody() ?>
                    <?php Box::end() ?>
                </div>
            </div>
        <?php endif ?>
        <?php
        $box = Box::begin(['renderBody' => false]);
        $box->beginHeader();
        echo $box->renderTitle(Yii::t('hipanel:server', 'Event log'));
        Box::endHeader();
        $box->beginBody();
        echo EventLog::widget([
            'statuses' => $model->statuses,
        ]);
        $box->endBody();
        Box::end();
        ?>
    </div>
</div>

<h4 class="mb-2" id="resource-consumption"><?= Yii::t('hipanel:server', 'Resource consumption') ?></h4>

<div class="row">
    <div class="col-md-12">
        <?= ConsumptionViewer::widget([
            'consumption' => $consumption,
            'mainObject' => $model,
            'showCharts' => false,
        ]) ?>
    </div>
</div>

<div class="row">
    <?php foreach ([
                       'server_traf' => Yii::t('hipanel:server', 'Traffic consumption'),
                       'server_traf95' => Yii::t('hipanel:server', 'Bandwidth consumption'),
                       'power' => Yii::t('hipanel:server', 'Power consumption'),
                   ] as $name => $label) : ?>
        <?php if (isset($chartsData[$name])) : ?>
            <div class="col-xs-12 col-md-4">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle($label);
                $box->beginTools();
                if ($name === 'power') {
                    echo PowerChartOptions::widget([
                        'id' => $name,
                        'form' => [
                            'action' => 'draw-chart',
                        ],
                        'hiddenInputs' => [
                            'id' => ['value' => $model->id],
                            'type' => ['value' => $name],
                        ],
                    ]);
                } else {
                    echo ChartOptions::widget([
                        'autoload' => true,
                        'id' => $name,
                        'form' => [
                            'action' => 'draw-chart',
                        ],
                        'hiddenInputs' => [
                            'id' => ['value' => $model->id],
                            'type' => ['value' => $name],
                        ],
                    ]);
                }
                $box->endTools();
                Box::endHeader();
                $box->beginBody();
                echo $this->render('_consumption', [
                    'labels' => $chartsLabels,
                    'data' => $chartsData,
                    'consumptionBase' => $name,
                ]);
                $box->endBody();
                Box::end();
                ?>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>

<div id="configuration" class="row">
    <div class="col-md-12">
        <?= Configuration::widget([
            'model' => $model,
            'configAttrs' => array_filter([
                'summary', 'summary_auto', 'comment',
                Yii::$app->user->can('order.read') && Yii::$app->user->can('owner-staff') ? 'order_no' : null,
                'units',
            ]),
        ]) ?>
    </div>
</div>

