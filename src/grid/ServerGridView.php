<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\Url;
use hipanel\modules\hosting\controllers\AccountController;
use hipanel\modules\hosting\controllers\IpController;
use hipanel\modules\server\menus\ServerActionsMenu;
use hipanel\modules\server\models\Binding;
use hipanel\modules\server\widgets\DiscountFormatter;
use hipanel\modules\server\widgets\Expires;
use hipanel\modules\server\widgets\OSFormatter;
use hipanel\modules\server\widgets\State;
use hipanel\widgets\ArraySpoiler;
use hipanel\widgets\gridLegend\ColorizeGrid;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\Label;
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ServerGridView extends \hipanel\grid\BoxedGridView
{
    use ColorizeGrid;

    public $controllerUrl = '@server';

    /**
     * @var array
     */
    public $osImages;

    protected function formatTariff($model)
    {
        if (Yii::$app->user->can('manage')) {
            if ($model->parent_tariff) {
                $title = Html::tag('abbr', $model->parent_tariff, ['title' => $model->tariff, 'data-toggle' => 'tooltip']);
            } else {
                $title = $model->tariff;
            }

            return Html::a($title, ['@tariff/view', 'id' => $model->tariff_id]);
        }

        return !empty($model->parent_tariff) ? $model->parent_tariff : $model->tariff;
    }

    public function columns()
    {
        return array_merge(parent::columns(), [
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
                'filterOptions' => ['class' => 'narrow-filter'],
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
                'format' => 'html',
                'contentOptions' => ['class' => 'text-uppercase'],
                'value' => function ($model) {
                    $value = $model->panel ? Yii::t('hipanel:server:panel', $model->panel) : Yii::t('hipanel:server:panel', 'No control panel');
                    if (Yii::$app->user->can('support')) {
                        $value .= $model->wizzarded ? Label::widget(['label' => 'W', 'tag' => 'sup', 'color' => 'success']) : '';
                    }

                    return $value;
                },
            ],
            'os' => [
                'attribute' => 'os',
                'format' => 'raw',
                'value' => function ($model) {
                    return OSFormatter::widget([
                        'osimages' => $this->osImages,
                        'imageName' => $model->osimage,
                    ]);
                },
            ],
            'os_and_panel' => [
                'attribute' => 'os',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = OSFormatter::widget([
                        'osimages' => $this->osImages,
                        'imageName' => $model->osimage,
                    ]);
                    $html .= ' ' . ($model->panel ?: '');

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
                    return $this->formatTariff($model);
                },
            ],
            'tariff_and_discount' => [
                'attribute' => 'tariff',
                'filterAttribute' => 'tariff_like',
                'format' => 'raw',
                'value' => function ($model) {
                    return $this->formatTariff($model) . ' ' . DiscountFormatter::widget([
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
                    return Html::tag('span', $model->type_label, ['class' => 'label label-default']);
                },
            ],
            'detailed_type' => [
                'label' => Yii::t('hipanel', 'Type'),
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return Html::tag('span', $model->type_label, ['class' => 'label label-default']);
                },
                'contentOptions' => function ($model) {
                    return GridLegend::create($this->findOrFailGridLegend($model))->gridColumnOptions('actions');
                },
            ],
            'rack' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return $this->renderSwitchPort($model->bindings['rack']);
                },
            ],
            'net' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return $this->renderSwitchPort($model->bindings['net']);
                },
            ],
            'kvm' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return $this->renderSwitchPort($model->bindings['kvm']);
                },
            ],
            'pdu' => [
                'format' => 'html',
                'filter' => false,
                'value'  => function ($model) {
                    return $this->renderSwitchPort($model->bindings['pdu']);
                },
            ],
            'ipmi' => [
                'format' => 'raw',
                'filter' => false,
                'value'  => function ($model) {
                    if (($ipmi = $model->getBinding('ipmi')) !== null) {
                        $link = Html::a($ipmi->device_ip, "http://$ipmi->device_ip/", ['target' => '_blank']) . ' ';

                        return $link . $this->renderSwitchPort($ipmi);
                    }
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
                'class' => MenuColumn::class,
                'menuClass' => ServerActionsMenu::class,
            ],
        ]);
    }

    /**
     * @param Binding $binding
     * @return string
     */
    protected function renderSwitchPort($binding)
    {
        if ($binding === null) {
            return '';
        }

        $label  = $binding->switch_label;
        $inn    = $binding->switch_inn;
        $inn    = $inn ? "($inn) " : '';
        $main   = $binding->switch . ($binding->port ? ':' . $binding->port : '');

        return "$inn<b>$main</b> $label";
    }
}
