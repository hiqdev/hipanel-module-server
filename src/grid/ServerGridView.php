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

use hipanel\base\Model;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\StringHelper;
use hipanel\helpers\Url;
use hipanel\modules\hosting\controllers\AccountController;
use hipanel\modules\hosting\controllers\IpController;
use hipanel\modules\server\menus\ServerActionsMenu;
use hipanel\modules\server\models\Consumption;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\widgets\DiscountFormatter;
use hipanel\modules\server\widgets\Expires;
use hipanel\modules\server\widgets\OSFormatter;
use hipanel\modules\server\widgets\ResourceConsumptionTable;
use hipanel\modules\server\widgets\State;
use hipanel\widgets\ArraySpoiler;
use hipanel\widgets\gridLegend\ColorizeGrid;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\Label;
use hiqdev\yii2\menus\grid\MenuColumn;
use Tuck\Sort\Sort;
use Yii;
use yii\data\ArrayDataProvider;
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
        if (Yii::$app->user->can('plan.read')) {
            if ($model->parent_tariff) {
                $title = Html::tag('abbr', $model->parent_tariff, [
                    'title' => $model->tariff, 'data-toggle' => 'tooltip',
                ]);
            } else {
                $title = $model->tariff;
            }

            return Html::a($title, ['@plan/view', 'id' => $model->tariff_id]);
        }

        return !empty($model->parent_tariff) ? $model->parent_tariff : $model->tariff;
    }

    public function columns()
    {
        $canAdmin = Yii::$app->user->can('admin');
        $canSupport = Yii::$app->user->can('support');

        return array_merge(parent::columns(), [
            'server' => [
                'class' => MainColumn::class,
                'attribute' => 'name',
                'filterAttribute' => 'name_like',
                'note' => Yii::$app->user->can('server.set-label') ? 'label' : 'note',
                'noteOptions' => [
                    'url' => Yii::$app->user->can('server.set-label') ? Url::to('set-label') : (Yii::$app->user->can('server.set-note') ? Url::to('set-note') : ''),
                ],
                'badges' => function ($model) use ($canSupport) {
                    $badges = '';
                    if ($canSupport) {
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
                'value' => function ($model) use ($canSupport) {
                    $value = $model->getPanel() ? Yii::t('hipanel:server:panel', $model->getPanel()) : Yii::t('hipanel:server:panel', 'No control panel');
                    if ($canSupport) {
                        $value .= $model->wizzarded ? Label::widget([
                            'label' => 'W', 'tag' => 'sup', 'color' => 'success',
                        ]) : '';
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
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'width:1%; white-space:nowrap;',
                ],
                'value' => function ($model) {
                    return ArraySpoiler::widget([
                        'data' => ArrayHelper::getColumn($model->ips, 'ip'),
                        'delimiter' => '<br />',
                        'visibleCount' => 1,
                        'formatter' => function ($ip, $idx) use ($model) {
                            if ($idx === 0) {
                                return Html::a($ip, IpController::getSearchUrl(['server_in' => $model->name]), [
                                    'class' => 'text-bold',
                                    'target' => '_blank',
                                ]);
                            }

                            return $ip;
                        },
                        'button' => [
                            'label' => Yii::t('hipanel:server', '') . ' +' . (count($model->ips) - 1),
                            'tag' => 'button',
                            'type' => 'button',
                            'class' => 'btn btn-xs btn-flat',
                            'style' => 'font-size: 10px',
                            'popoverOptions' => [
                                'html' => true,
                                'placement' => 'bottom',
                                'title' => Yii::t('hipanel:server', 'IPs'),
                                'template' => '
                                    <div class="popover" role="tooltip">
                                        <div class="arrow"></div>
                                        <h3 class="popover-title"></h3>
                                        <div class="popover-content" style="min-width: 15rem; height: 15rem; overflow-x: scroll;"></div>
                                    </div>
                                ',
                            ],
                        ]
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
                    'url' => Url::to('set-note'),
                ],
                'widgetOptions' => [
                    'linkOptions' => [
                        'data-type' => 'textarea',
                    ],
                ],
                'visible' => Yii::$app->user->can('server.set-note'),
            ],
            'label' => [
                'class' => XEditableColumn::class,
                'pluginOptions' => [
                    'url' => Url::to('set-label'),
                ],
                'widgetOptions' => [
                    'linkOptions' => [
                        'data-type' => 'textarea',
                    ],
                ],
                'visible' => Yii::$app->user->can('server.set-label'),
            ],
            'type' => [
                'format' => 'html',
                'filter' => false,
                'value' => function ($model) {
                    return Html::tag('span', $model->type_label, ['class' => 'label label-default']);
                },
            ],
            'detailed_type' => [
                'label' => Yii::t('hipanel', 'Type'),
                'format' => 'html',
                'filter' => false,
                'value' => function ($model) {
                    return Html::tag('span', $model->type_label, ['class' => 'label label-default']);
                },
                'contentOptions' => function ($model) {
                    return GridLegend::create($this->findOrFailGridLegend($model))->gridColumnOptions('actions');
                },
            ],
            'rack' => [
                'class' => BindingColumn::class,
            ],
            'net' => [
                'class' => BindingColumn::class,
            ],
            'kvm' => [
                'class' => BindingColumn::class,
            ],
            'pdu' => [
                'class' => BindingColumn::class,
            ],
            'ipmi' => [
                'class' => BindingColumn::class,
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
            'monthly_fee' => [
                'label' => Yii::t('hipanel:finance', 'Monthly fee'),
                'format' => 'html',
                'filter' => false,
                'value' => function ($model) {
                    return $this->getMonthlyFee($model);
                },
                'visible' => Yii::$app->user->can('consumption.read'),
            ],
            'traffic' => [
                'label' => Yii::t('hipanel:server', 'Traffic'),
                'format' => 'html',
                'filter' => false,
                'value' => function ($model) {
                    return isset($model->consumptions['overuse,server_traf_max']) ? $this->getFormattedConsumptionFor($model->consumptions['overuse,server_traf_max']) : null;
                },
                'visible' => Yii::$app->user->can('consumption.read'),
            ],
            'additional_services' => [
                'label' => Yii::t('hipanel:server', 'Additional services'),
                'format' => 'raw',
                'filter' => false,
                'contentOptions' => ['class' => 'no-padding'],
                'value' => function ($model) {
                    return $this->getAdditionalServices($model);
                },
                'visible' => Yii::$app->user->can('consumption.read'),
            ],
            'type_of_sale' => [
                'label' => Yii::t('hipanel:server', 'Type of sale'),
                'format' => 'raw',
                'filter' => false,
                'value' => function (Server $model) {
                    return $this->getTypeOfSale($model);
                },
                'visible' => Yii::$app->user->can('consumption.read'),
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => ServerActionsMenu::class,
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'width:1%; white-space:nowrap;',
                ],
            ],
        ]);
    }

    private function getFormattedConsumptionFor(Consumption $consumption): string
    {
        $result = '';
        $widget = Yii::createObject(['class' => ResourceConsumptionTable::class, 'model' => $consumption]);

        if ($limit = $widget->getFormatted($consumption, $consumption->limit)) {
            $result .= sprintf('%s: %s<br />',
                Html::tag('b', Yii::t('hipanel:server', 'included')),
                $limit
            );
        }
        if ($price = $consumption->getFormattedPrice()) {
            $result .= sprintf('%s: %s',
                Html::tag('b', Yii::t('hipanel:server', 'price')),
                $price
            );
        }

        return $result;
    }

    private function getMonthlyFee($model): string
    {
        $unionConsumption = new Consumption();
        $prices = [];
        if ($model->consumptions) {
            array_walk($model->consumptions, function (Consumption $consumption) use (&$prices) {
                if ($consumption->type && $consumption->hasFormattedAttributes() && StringHelper::startsWith($consumption->type, 'monthly,')) {
                    if ($consumption->price) {
                        $consumption->setAttribute('prices', [$consumption->currency => $consumption->price]);
                    }
                    foreach ($consumption->prices as $currency => $price) {
                        $prices[$currency] += $price;
                    }
                }
            });
        }
        $unionConsumption->setAttribute('prices', $prices);

        return $unionConsumption->getFormattedPrice();
    }

    private function getAdditionalServices($model): string
    {
        $additional = new class() extends Model
        {
            /**
             * @var string
             */
            public $typeLabel;

            /**
             * @var string
             */
            public $value;
        };
        $models = [];
        foreach (['overuse,support_time', 'overuse,backup_du', 'monthly,win_license'] as $type) {
            if (isset($model->consumptions[$type]) && $model->consumptions[$type]->hasFormattedAttributes()) {
                $consumption = $model->consumptions[$type];
                $models[] = new $additional([
                    'typeLabel' => Yii::t('hipanel.server.consumption.type', $consumption->typeLabel),
                    'value' => $this->getFormattedConsumptionFor($consumption),
                ]);
            }
        }

        return \yii\grid\GridView::widget([
            'layout' => '{items}',
            'showOnEmpty' => false,
            'emptyText' => '',
            'tableOptions' => ['class' => 'table table-striped table-condensed'],
            'headerRowOptions' => [
                'style' => 'display: none;',
            ],
            'dataProvider' => new ArrayDataProvider(['allModels' => $models, 'pagination' => false]),
            'columns' => [
                [
                    'attribute' => 'typeLabel',
                ],
                [
                    'attribute' => 'value',
                    'format' => 'html',
                ],
            ],
        ]);
    }

    private function getTypeOfSale($model): string
    {
        $html = '';
        $badgeColors = [
            'leasing' => 'bg-orange',
            'rent' => 'bg-purple',
            'sold' => 'bg-olive',
        ];

        if (empty($model->hardwareSales)) {
            return $html;
        }

        foreach ($model->hardwareSales as $saleType => $sales) {
            $html .= ArraySpoiler::widget([
                'data' => Sort::by($sales, function ($sale) {
                    $order = ['CHASSIS', 'MOTHERBOARD', 'CPU', 'RAM', 'HDD', 'SSD'];
                    $type = substr($sale['part'], 0, strpos($sale['part'], ':'));
                    $key = array_search($type, $order, true);
                    if ($key !== false) {
                        return $key;
                    }

                    return INF;
                }),
                'delimiter' => '<br/>',
                'visibleCount' => 0,
                'button' => [
                    'label' => (function() use ($saleType, $sales) {
                        if ($saleType === 'leasing') {
                            /** @var \DateTime $maxLeasingDate */
                            $maxLeasingDate = array_reduce($sales, function (\DateTime $max, $item) {
                                $date = new \DateTime($item['leasing_till']);
                                return $date > $max ? $date : $max;
                            }, new \DateTime());

                            return Yii::t('hipanel:server', $saleType) . ' ' . \count($sales)
                                . '<br />' . $maxLeasingDate->format('d.m.Y');
                        }

                        return Yii::t('hipanel:server', $saleType) . ' ' . \count($sales);
                    })(),
                    'tag' => 'button',
                    'type' => 'button',
                    'class' => "btn btn-xs {$badgeColors[$saleType]}",
                    'popoverOptions' => [
                        'html' => true,
                        'placement' => 'bottom',
                        'title' => Yii::t('hipanel:stock', 'Parts'),
                        'template' => '
                            <div class="popover" role="tooltip">
                                <div class="arrow"></div>
                                <h3 class="popover-title"></h3>
                                <div class="popover-content" style="height: 25rem; overflow-x: scroll;"></div>
                            </div>
                        ',

                    ],
                ],
                'formatter' => function ($item) {
                    $additionalInfo = null;
                    $title = $item['part'];
                    if (isset($item['serialno'])) {
                        $title .= ': ' .$item['serialno'];
                    }

                    if (isset($item['leasing_till'])) {
                        $additionalInfo = Yii::t('hipanel:server', '{since} &mdash; {till}', [
                            'since' => Yii::$app->formatter->asDate($item['leasing_since'], 'short'),
                            'till' => Yii::$app->formatter->asDate($item['leasing_till'], 'short'),
                        ]);
                    }

                    if (isset($item['sale_time'])) {
                        $additionalInfo = Yii::t('hipanel:server', 'since {date}', [
                            'date' => Yii::$app->formatter->asDate($item['sale_time'], 'short'),
                        ]);
                    }

                    return Html::a(
                        $title,
                        ['@part/view', 'id' => $item['part_id']],
                        ['class' => 'text-nowrap', 'target' => '_blank']
                    ) . ($additionalInfo ? " ($additionalInfo)" : '');
                },
            ]);
        }

        return $html;
    }
}
