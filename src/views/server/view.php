<?php

use hipanel\helpers\Url;
use hipanel\widgets\EventLog;
use hipanel\modules\server\assets\ServerTaskCheckerAsset;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\menus\ServerDetailMenu;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\widgets\ChartOptions;
use hipanel\modules\server\widgets\SimpleOperation;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use hipanel\widgets\ClientSellerLink;
use hipanel\widgets\SettingsModal;
use hipanel\modules\server\widgets\Wizzard;
use hipanel\modules\server\widgets\BootLive;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * @var $model Server
 */

$this->title = $model->name;
$this->params['subtitle'] = Yii::t('hipanel:server', 'Server detailed information') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

list($chartsLabels, $chartsData) = $model->groupUsesForCharts();

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
                <span class="profile-user-name"><?= ClientSellerLink::widget(['model' => $model]) ?></span>
            </p>
            <?php Pjax::begin(['enablePushState' => false]) ?>
            <div class="profile-usermenu">
                <?= ServerDetailMenu::widget(['model' => $model, 'blockReasons' => $blockReasons]) ?>
            </div>
            <?php Pjax::end() ?>
            <?php Box::end() ?>

            <?php if ($model->isVNCSupported()) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                        echo $box->renderTitle(Yii::t('hipanel:server', 'VNC server'));
                        $box->endHeader();
                        $box->beginBody();
                        echo $this->render('_vnc', compact(['model']));
                        $box->endBody();
                        $box->end();
                        ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                    echo $box->renderTitle(Yii::t('hipanel:server', 'System management'));
                    $box->endHeader();
                    $box->beginBody() ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <?= SimpleOperation::widget([
                                'model' => $model,
                                'scenario' => 'reboot',
                                'buttonLabel' => Yii::t('hipanel:server', 'Reboot'),
                                'body' => '<div class="callout callout-warning">
    <h4>' . Yii::t('hipanel:server', 'This may cause data loose!') . '</h4>
</div>
<p>' . Yii::t('hipanel:server', 'Reboot will interrupt all processes on the server. Are you sure you want to reset the server?') . '</p>',
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
<p>' . Yii::t('hipanel:server', 'Shutdown will interrupt all processes on the server. Are you sure you want to shutdown the server?') . '</p>',
                                'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm server shutdown'),
                                'modalHeaderOptions' => ['class' => 'label-warning'],
                                'modalFooterLabel' => Yii::t('hipanel:server', 'Shutdown'),
                                'modalFooterLoading' => Yii::t('hipanel:server', 'Shutting down...'),
                                'modalFooterClass' => 'btn btn-warning',
                            ]) ?>
                        </div>
                        <?php if ($model->isLiveCDSupported()) : ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <?= BootLive::widget([
                                    'model' => $model,
                                    'osimageslivecd' => $osimageslivecd,
                                ]) ?>
                            </div>
                        <?php endif ?>
                        <?php if ($model->isVirtualDevice()) : ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <?= $this->render('_reinstall', compact(['model', 'groupedOsimages', 'panels'])) ?>
                            </div>
                        <?php endif ?>
                        <?php /** if ($model->isDedicatedDevice()) : **/ ?>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <?= Wizzard::widget(compact(['model'])) ?>

                        </div>
                        <?php /** endif **/ ?>
                    </div>
                    <?php
                    $box->endBody();
                    $box->end();
                    ?>
                </div>
            </div>
            <?php if ($model->isVirtualDevice()) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                        echo $box->renderTitle(Yii::t('hipanel:server', 'Power management'));
                        $box->endHeader();
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
<p>' . Yii::t('hipanel:server', 'Power off will immediately interrupt all processes on the server in a dangerous way. Always try to shutdown it, before turning off the power. Are you sure you want to power off the server?') . '</p>',
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
<p>' . Yii::t('hipanel:server', 'Power reset will interrupt all processes on the server in a dangerous way. Always try to reboot it, before resetting. Are you sure you want to reset power of the server?') . '</p>',
                                    'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm server power reset'),
                                    'modalHeaderOptions' => ['class' => 'label-warning'],
                                    'modalFooterLabel' => Yii::t('hipanel:server', 'Reset power'),
                                    'modalFooterLoading' => Yii::t('hipanel:server', 'Resetting power...'),
                                    'modalFooterClass' => 'btn btn-warning',
                                ]) ?>
                            </div>
                        </div>
                        <?php $box->endBody(); $box->end(); ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                    echo $box->renderTitle(Yii::t('hipanel:server', 'Event log'));
                    $box->endHeader();
                    $box->beginBody();
                    echo EventLog::widget([
                        'statuses' => $model->statuses,
                    ]);
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
                        ]
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
                    echo $box->renderTitle(Yii::t('hipanel:server', 'Server information'));
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
                            'state', 'os', 'panel',
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
                    echo ServerGridView::detailView([
                        'boxed'   => false,
                        'model'   => $model,
                        'columns' => [
                            'tariff', 'sale_time', 'discount', 'expires',
                        ],
                    ]);
                    $box->endBody();
                    $box->beginFooter();
                    if ($model->autorenewal || in_array($model->state, $model->goodStates(), true)) {
                        echo SimpleOperation::widget([
                            'model' => $model,
                            'scenario' => $model->autorenewal ? 'refuse' : 'enable-autorenewal',
                            'buttonLabel' => $model->autorenewal ? Yii::t('hipanel:server', 'Refuse service') : Yii::t('hipanel:server', 'Renew service'),
                            'buttonClass' => 'btn btn-default',
                            'body' => function ($model) {
                                if (!$model->autorenewal) {
                                    return Yii::t('hipanel:server', 'Are you sure, you want to renew the service?');
                                }
                                return $model->canFullRefuse()
                                    ? Yii::t('hipanel:server', 'In case of service refusing, the server will be locked and turned off. All data on the server will be removed!')
                                    : Yii::t('hipanel:server', 'In case of service refusing, the server will be locked and turned off {0, date, medium}. All data on the server will be removed!', Yii::$app->formatter->asTimestamp($model->expires));
                            },
                            'modalHeaderLabel' => $model->autorenewal ? Yii::t('hipanel:server', 'Confirm service refuse') : Yii::t('hipanel:server', 'Confirm service renewal'),
                            'modalHeaderOptions' => ['class' => $model->autorenewal ? 'label-danger' : 'label-info'],
                            'modalFooterLabel' => $model->autorenewal ? Yii::t('hipanel:server', 'Refuse') : Yii::t('hipanel:server', 'Renew'),
                            'modalFooterLoading' => $model->autorenewal ? Yii::t('hipanel:server', 'Refusing...') : Yii::t('hipanel:server', 'Renewing...'),
                            'modalFooterClass' => $model->autorenewal ? 'btn btn-warning': 'btn btn-info',
                        ]);
                    }
                    if (Yii::$app->user->can('manage')) {
                        echo SettingsModal::widget([
                            'model'    => $model,
                            'title'    => Yii::t('hipanel:server', 'Change tariff'),
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
            <?php if (Yii::getAlias('@part', false) && Yii::$app->user->can('support')) : ?>
                <div class="row">
                    <?php Pjax::begin(['enablePushState' => false]) ?>
                    <div class="col-md-12">
                        <?php $box = Box::begin(['renderBody' => false]) ?>
                        <?php $box->beginHeader() ?>
                        <?= $box->renderTitle(Yii::t('hipanel:server', 'Configuration')) ?>
                        <?php $box->endHeader() ?>
                        <?php $box->beginBody() ?>
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
        <div class="col-md-5">
            <?php echo $this->render('_ip', ['model' => $model]) ?>
            <?php if (isset($chartsData['server_traf'])) : ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                    echo $box->renderTitle(Yii::t('hipanel:server', 'Traffic consumption'));
                    $box->beginTools();
                    echo ChartOptions::widget([
                        'id' => 'server_traf',
                        'form' => [
                            'action' => 'draw-chart'
                        ],
                        'hiddenInputs' => [
                            'id' => ['value' => $model->id],
                            'type' => ['value' => 'traffic'],
                        ]
                    ]);
                    $box->endTools();
                    $box->endHeader();
                    $box->beginBody();
                    echo $this->render('_consumption', [
                        'labels' => $chartsLabels,
                        'data' => $chartsData,
                        'consumptionBase' => 'server_traf',
                        'isClientRegisterCss' => true,
                    ]);
                    $box->endBody();
                    $box->end();
                    ?>
                </div>
                <?php endif ?>
            </div>
            <?php if (isset($chartsData['server_traf95'])) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $box = Box::begin(['renderBody' => false]);
                        $box->beginHeader();
                        echo $box->renderTitle(Yii::t('hipanel:server', 'Bandwidth consumption'));
                        $box->beginTools();
                        echo ChartOptions::widget([
                            'id' => 'server_traf95',
                            'form' => [
                                'action' => 'draw-chart'
                            ],
                            'hiddenInputs' => [
                                'id' => ['value' => $model->id],
                                'type' => ['value' => 'bandwidth'],
                            ]
                        ]);
                        $box->endTools();
                        $box->endHeader();
                        $box->beginBody();
                        echo $this->render('_consumption', [
                            'labels' => $chartsLabels,
                            'data' => $chartsData,
                            'consumptionBase' => 'server_traf95',
                        ]);
                        $box->endBody();
                        $box->end();
                        ?>
                    </div>
                </div>
            <?php endif ?>
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
