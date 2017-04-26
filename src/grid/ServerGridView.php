<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\Url;
use hipanel\modules\hosting\controllers\AccountController;
use hipanel\modules\hosting\controllers\IpController;
use hipanel\modules\server\widgets\DiscountFormatter;
use hipanel\modules\server\widgets\Expires;
use hipanel\modules\server\widgets\OSFormatter;
use hipanel\modules\server\widgets\State;
use hipanel\widgets\ArraySpoiler;
use hipanel\widgets\Label;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ServerGridView extends \hipanel\grid\BoxedGridView
{
    public $controllerUrl = '@server';

    /**
     * @var array
     */
    public static $osImages;

    public static function setOsImages($osImages)
    {
        static::$osImages = $osImages;
    }

    public static function formatTariff($model)
    {
        if (Yii::$app->user->can('manage')) {
            if ($model->parent_tariff) {
                $html[] = Html::tag('abbr', $model->parent_tariff, ['title' => $model->tariff, 'data-toggle' => 'tooltip']);
            } else {
                $html[] = $model->tariff;
            }

            $html[] = Html::a('<i class="fa fa-external-link"></i>', ['@tariff/view', 'id' => $model->tariff_id]);

            return implode(' ', $html);
        }

        return !empty($model->parent_tariff) ? $model->parent_tariff : $model->tariff;
    }

    public static function defaultColumns()
    {
        $osImages = self::$osImages;

        return [
            'server' => [
                'class' => MainColumn::class,
                'attribute' => 'name',
                'filterAttribute' => 'name_like',
                'note' => Yii::$app->user->can('support') ? 'label' : 'note',
                'noteOptions' => [
                    'url' => Yii::$app->user->can('support') ? Url::to('set-label') : Url::to('set-note'),
                ],
                'badges' => function ($model) {
                    $badges = '';
                    if (Yii::$app->user->can('support')) {
                        if ($model->wizzarded) {
                            $badges .= Label::widget(['label' => 'W', 'tag' => 'sup', 'color' => 'success']);
                        }
                        /*if ($model->state === 'disabled') {
                            $badges .= ' ' . Label::widget(['label' => 'Panel OFF', 'tag' => 'sup', 'color' => 'danger', 'type' => 'text']);
                        }*/
                    }

                    return $badges;
                },
            ],
            'dc' => [
                'attribute' => 'dc',
                'filter' => false,
            ],
            'state' => [
                'class' => RefColumn::class,
                'i18nDictionary' => 'hipanel:server',
                'format' => 'raw',
                'gtype' => 'state,device',
                'value' => function ($model) {
                    $html = State::widget(compact('model'));
                    if ($model->status_time) {
                        $html .= ' ' . Html::tag('nobr', Yii::t('hipanel:server', 'since {date}', ['date' => Yii::$app->formatter->asDate($model->status_time)]));
                    }
                    return $html;
                },
            ],
            'panel' => [
                'attribute' => 'panel',
                'format' => 'text',
                'contentOptions' => ['class' => 'text-uppercase'],
                'value' => function ($model) {
                    return $model->panel ? Yii::t('hipanel:server:panel', $model->panel) : Yii::t('hipanel:server:panel', 'No control panel');
                },
            ],
            'os' => [
                'attribute' => 'os',
                'format' => 'raw',
                'value' => function ($model) use ($osImages) {
                    return OSFormatter::widget([
                        'osimages' => $osImages,
                        'imageName' => $model->osimage,
                    ]);
                },
            ],
            'os_and_panel' => [
                'attribute' => 'os',
                'format' => 'raw',
                'value' => function ($model) use ($osImages) {
                    $html = OSFormatter::widget([
                        'osimages' => $osImages,
                        'imageName' => $model->osimage,
                    ]);
                    $html .= ' ' . $model->panel ?: '';
                    return $html;
                },
            ],
            'discount' => [
                'attribute' => 'discount',
                'label' => Yii::t('hipanel:server', 'Discount'),
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 1em'],
                'value' => function ($model) {
                    return DiscountFormatter::widget([
                        'current' => $model->discounts['fee']['current'],
                        'next' => $model->discounts['fee']['next'],
                    ]);
                },
            ],
            'expires' => [
                'filter' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 1em'],
                'value' => function ($model) {
                    return Expires::widget(compact('model'));
                },
            ],
            'tariff' => [
                'format' => 'raw',
                'filterAttribute' => 'tariff_like',
                'value' => function ($model) {
                    return self::formatTariff($model);
                },
            ],
            'tariff_and_discount' => [
                'attribute' => 'tariff',
                'filterAttribute' => 'tariff_like',
                'format' => 'raw',
                'value' => function ($model) {
                    return self::formatTariff($model) . ' ' . DiscountFormatter::widget([
                        'current' => $model->discounts['fee']['current'],
                        'next' => $model->discounts['fee']['next'],
                    ]);
                },
            ],
            'ip' => [
                'filter' => false,
            ],
            'mac' => [
                'filter' => false,
            ],
            'ips' => [
                'format' => 'raw',
                'attribute' => 'ips',
                'filter' => false,
                'value' => function ($model) {
                    return ArraySpoiler::widget([
                        'data' => ArrayHelper::getColumn($model->ips, 'ip'),
                        'delimiter' => '<br />',
                        'visibleCount' => 3,
                        'button' => ['popoverOptions' => ['html' => true]],
                    ]);
                },
            ],
            'sale_time' => [
                'attribute' => 'sale_time',
                'format' => 'datetime',
            ],
            'note' => [
                'class' => XEditableColumn::class,
                'pluginOptions' => [
                    'url'       => Url::to('set-note'),
                ],
                'widgetOptions' => [
                    'linkOptions' => [
                        'data-type' => 'textarea',
                    ],
                ],
            ],
            'label' => [
                'class' => XEditableColumn::class,
                'visible' => Yii::$app->user->can('support'),
                'pluginOptions' => [
                    'url'       => Url::to('set-label'),
                ],
                'widgetOptions' => [
                    'linkOptions' => [
                        'data-type' => 'textarea',
                    ],
                ],
            ],
            'type' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return $model->type_label;
                },
            ],
            'rack' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return $model->switches['rack']['switch'];
                },
            ],
            'net' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return static::renderSwitchPort($model->switches['net']);
                },
            ],
            'kvm' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return static::renderSwitchPort($model->switches['kvm']);
                },
            ],
            'pdu' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return static::renderSwitchPort($model->switches['pdu']);
                },
            ],
            'ipmi' => [
                'format' => 'raw',
                'filter' => false,
                'value'  => function ($model) {
                    $ipmi = $model->switches['ipmi']['device_ip'];
                    $link = $ipmi ? Html::a($ipmi, "http://$ipmi/", ['target' => '_blank']) . ' ' : '';
                    return $link . static::renderSwitchPort($model->switches['ipmi']);
                },
            ],
            'nums' => [
                'label' => '',
                'format' => 'raw',
                'value' => function ($model) {
                    $ips_num = $model->ips_num;
                    $ips = $ips_num ? Html::a("$ips_num ips", IpController::getSearchUrl(['server' => $model->name])) : 'no ips';
                    $act_acs_num = $model->acs_num - $model->del_acs_num;
                    $del_acs_num = $model->del_acs_num;
                    $acs_num = $act_acs_num . ($del_acs_num ? "+$del_acs_num" : '');
                    $acs = $acs_num ? Html::a("$acs_num acc", AccountController::getSearchUrl(['server' => $model->name])) : 'no acc';
                    return Html::tag('nobr', $ips) . ' ' . Html::tag('nobr', $acs);
                },
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {rrd} {switch-graph}',
                'buttons' => [
                    'switch-graph' => function ($url, $model) {
                        return Html::a('<i class="fa fa-fw fa-area-chart"></i>' . Yii::t('hipanel:server', 'Switch graphs'), ['@switch-graph/view', 'id' => $model->id]);
                    },
                    'rrd' => function ($url, $model) {
                        return Html::a('<i class="fa fa-fw fa-signal"></i>' . Yii::t('hipanel:server', 'Resources usage graphs'), ['@rrd/view', 'id' => $model->id]);
                    },
                ],
            ],
        ];
    }

    public static function renderSwitchPort($data)
    {
        $label  = $data['switch_label'];
        $inn    = $data['switch_inn'];
        $name   = $data['switch'];
        $port   = $data['port'];

        $inn    = $inn ? "($inn)" : '';
        $main   = $port ? "$name:$port" : $name;
        $main   = $main ? "<b>$main</b>" : '';

        return "$inn $main $label";
    }

    public static function defaultRepresentations()
    {
        return [
            'common' => [
                'label'   => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'server', 'client_like', 'seller_id',
                    'ips', 'state', 'expires',
                    'tariff_and_discount',
                ],
            ],
            'manager' => Yii::$app->user->can('support') ? [
                'label'   => Yii::t('hipanel:server', 'manager'),
                'columns' => [
                    'checkbox', 'client_like',
                    'rack', 'server', 'tariff',
                    'hwsummary', 'nums', 'actions',
                ],
            ] : null,
            'admin' => Yii::$app->user->can('support') ? [
                'label'   => Yii::t('hipanel:server', 'admin'),
                'columns' => [
                    'checkbox', 'dc', 'server', 'type',
                    'net', 'kvm', 'ipmi', 'pdu', 'ip', 'mac',
                ],
            ] : null,
        ];
    }
}
