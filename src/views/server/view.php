<?php

use hipanel\modules\server\assets\OsSelectionAsset;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\Server;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use hipanel\widgets\ClientSellerLink;
use yii\bootstrap\Html;
use yii\web\JsExpression;

/**
 * @var $model Server
 */

$this->title    = $model->name;
$this->subtitle = Yii::t('hipanel/server', 'Server detailed information') . ' #' . $model->id;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('hipanel/server', 'Servers'), 'url' => ['index']],
    $this->title,
]);

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
                                'state', 'ips', 'os', 'panel'
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
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                        echo $box->renderTitle(Yii::t('hipanel/server', 'Traffic consumption'));
                        $box->beginTools();
                            echo Html::beginForm(['draw-chart'], 'post', ['class' => 'form-inline traffic-consumption']);
                                echo Html::hiddenInput('id', $model->id);
                                echo Html::hiddenInput('type', 'traffic');
                                echo Html::hiddenInput('from');
                                echo Html::hiddenInput('till'); ?>

                                <div class="form-group">
                                    <button type="button" class="btn btn-sm" id="traffic-consumption-period-btn">
                                        <i class="fa fa-calendar"></i>
                                            <span data-prompt="<?= Yii::t('hipanel/server', 'Interval') ?>">
                                                <?= Yii::t('hipanel/server', 'Interval') ?>
                                            </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>

                                    <?= Html::dropDownList('aggregation', 'month', [
                                        'day' => Yii::t('hipanel/server', 'Daily'),
                                        'week' => Yii::t('hipanel/server', 'Weekly'),
                                        'month' => Yii::t('hipanel/server', 'Monthly'),
                                    ], ['class' => 'form-control input-sm']) ?>
                                </div>

                            <?php
                            echo \omnilight\widgets\DateRangePicker::widget([
                                'name' => false,
                                'options' => [
                                    'tag' => false,
                                    'id' => 'traffic-consumption-period-btn'
                                ],
                                'clientEvents' => [
                                    'apply.daterangepicker' => new JsExpression(/** @lang JavaScript */"
                                        function (event, picker) {
                                            var form = $(picker.element[0]).closest('form');
                                            var span = form.find('#traffic-consumption-period-btn span');

                                            span.text(picker.startDate.format('ll') + ' - ' + picker.endDate.format('ll'));

                                            form.find('input[name=from]').val(picker.startDate.format());
                                            form.find('input[name=till]').val(picker.endDate.format());
                                            form.trigger('change.updateChart');
                                        }
                                    "),
                                    'cancel.daterangepicker' => new JsExpression(/** @lang JavaScript */"
                                        function (event, picker) {
                                            var form = $(event.element[0]).closest('form');
                                            var span = form.find('#traffic-consumption-period-btn span');

                                            span.text(span.data('prompt'));

                                            form.find('input[name=from]').val('');
                                            form.find('input[name=till]').val('');
                                            form.trigger('change.updateChart');
                                        }
                                    "),
                                ],
                                'clientOptions' => [
                                    'ranges' => [
                                        Yii::t('hipanel/server', 'Last Month') => new JsExpression('[moment().subtract("month", 1).startOf("month"), new Date()]'),
                                        Yii::t('hipanel/server', 'Last 3 months') => new JsExpression('[moment().subtract("month", 3).startOf("month"), new Date()]'),
                                        Yii::t('hipanel/server', 'Last year') => new JsExpression('[moment().subtract("year", 1).startOf("year"), new Date()]'),
                                    ]
                                ]
                            ]);

                            $this->registerJs(/** @lang JavaScript */"
                                $('.traffic-consumption').on('change.updateChart', function (event) {
                                    var defaultOptions = {
                                        url: $(this).attr('action'),
                                        data: $(this).serializeArray(),
                                        type: 'post',
                                        beforeSend: function () {
                                            $('.traffic-consumption').closest('.box').append($('<div>').addClass('overlay').html($('<i>').addClass('fa fa-refresh fa-spin')));
                                        },
                                        success: function (html) {
                                            $('.traffic-consumption').closest('.box').find('.overlay').remove();
                                            $('.traffic-consumption-chart-wrapper').replaceWith(html);
                                        }
                                    };
                                    event.preventDefault();
                                    $.ajax(defaultOptions);
                                });
                            "); ?>
                        <?php
                        echo Html::endForm();
                        $box->endTools();
                    $box->endHeader();
                    $box->beginBody();
                        list($labels, $data) = $model->groupUsesForCharts();
                        echo $this->render('_traffic_consumption', ['labels' => $labels, 'data' => $data]);
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
                        echo $box->renderTitle(Yii::t('hipanel/server', 'Bandwidth consumption'));
                    $box->endHeader();
                    $box->beginBody();
                        list($labels, $data) = $model->groupUsesForCharts();
                        echo $this->render('_bandwidth_consumption', ['labels' => $labels, 'data' => $data]);
                    $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss("th { white-space: nowrap; }");
Pjax::end();
