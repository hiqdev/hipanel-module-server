<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\base\Model;
use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\StringHelper;
use hipanel\helpers\Url;
use hipanel\modules\finance\models\Sale;
use hipanel\modules\hosting\controllers\AccountController;
use hipanel\modules\hosting\controllers\IpController;
use hipanel\modules\server\menus\ServerActionsMenu;
use hipanel\modules\server\models\Consumption;
use hipanel\modules\server\models\HardwareSale;
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

class ServerGridView extends BoxedGridView
{
    use ColorizeGrid;

    public $controllerUrl = '@server';

    /**
     * @var array
     */
    public $osImages;

    public function init()
    {
        parent::init();

        $this->view->registerCss('
        .tariff-chain {
            list-style: none;
            background-color: transparent;
        }
        .tariff-chain > li {
            display: inline-block;
        }
        .tariff-chain > li + li:before {
            font: normal normal normal 14px/1 FontAwesome;
            content: "\f178\00a0";
            padding: 0 5px;
            color: #ccc;
        }
        .inactiveLink {
           pointer-events: none;
           cursor: default;
        }
        ');
    }

    protected function formatTariff($model)
    {
        $user = Yii::$app->user;
        $models = [];
        $html = '';
        if ($user->can('sale.read') && !empty($model->sales)) {
            foreach ($model->sales as $sale) {
                $models[] = $sale;
            }
        } elseif ($user->can('plan.read')) {
            if (!empty($model->parent_tariff)) {
                $title = $model->parent_tariff;
            } else {
                $title = $model->tariff;
            }

            $models[] = new Sale(['tariff' => $title, 'tariff_id' => $model->tariff_id]);
        } else {
            $models[] = new Sale([
                'tariff' => $model->tariff,
                'tariff_id' => $model->tariff_id,
            ]);
        }

        foreach ($models as $model) {
            if ($model->tariff) {
                $tariff = Html::encode($model->tariff);
                $client = Html::encode($model->seller);
                $tariff = $model->tariff_id ? Html::a($tariff, [
                    '@plan/view',
                    'id' => $model->tariff_id,
                ]) : $tariff;
                $client = $model->seller ? '(' . Html::a($client, [
                    '@client/view', 'id' => $model->seller_id,
                ]) . ')' : '';

                $html .= Html::tag('li', $tariff . '&nbsp;' . $client);
            }
        }

        return Html::tag('ul', $html, [
            'class' => 'tariff-chain ' . ($user->can('support') ?: 'inactiveLink'),
            'style' => 'margin: 0; padding: 0;',
        ]);
    }

    public function columns()
    {
        $canAdmin = Yii::$app->user->can('admin');
        $canSupport = Yii::$app->user->can('support');

        return array_merge(parent::columns(), [
            'server' => [
                'class' => ServerNameColumn::class,
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
                'visible' => $canSupport,
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
                'format' => 'raw',
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
                        'current' => Html::encode($model->discounts['fee']['current']),
                        'next' => Html::encode($model->discounts['fee']['next']),
                    ]);
                },
            ],
            'expires' => [
                'filter' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 1em'],
                'visible' => $canSupport,
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
                            $ip = Html::encode($ip);
                            if ($idx === 0) {
                                return Html::a($ip, IpController::getSearchUrl(['server_in' => Html::encode($model->name)]), [
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
                        ],
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
            'label' => Yii::$app->user->can('server.set-label') ? [
                'class' => XEditableColumn::class,
                'pluginOptions' => [
                    'url' => Url::to('set-label'),
                ],
                'widgetOptions' => [
                    'linkOptions' => [
                        'data-type' => 'textarea',
                    ],
                ],
                'visible' => Yii::$app->user->can('server.see-label'),
            ] : [
                'visible' => Yii::$app->user->can('server.see-label'),
            ],
            'type' => [
                'format' => 'raw',
                'filter' => false,
                'value' => function ($model) {
                    return Html::tag('span', Html::encode($model->type_label), ['class' => 'label label-default']);
                },
            ],
            'detailed_type' => [
                'label' => Yii::t('hipanel', 'Type'),
                'format' => 'raw',
                'filter' => false,
                'value' => function ($model) {
                    return Html::tag('span', Html::encode($model->type_label), ['class' => 'label label-default']);
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
            'location' => [
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
                'format' => 'raw',
                'filter' => false,
                'value' => function ($model) {
                    return $this->getMonthlyFee($model);
                },
                'visible' => Yii::$app->user->can('consumption.read'),
            ],
            'traffic' => [
                'label' => Yii::t('hipanel:server', 'Traffic'),
                'format' => 'raw',
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
            'hwsummary' => [
                'filterAttribute' => 'hwsummary_like',
                'label' => Yii::t('hipanel:server', 'Hardware Summary'),
            ],
            'hwcomment' => [
                'filter' => false,
                'label' => Yii::t('hipanel:server', 'Hardware Comment'),
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
        $additional = new class() extends Model {
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
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'value',
                    'format' => 'raw'
                ],
            ],
        ]);
    }

    private function getTypeOfSale(Server $model): string
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

        $salesByType = [
            HardwareSale::USAGE_TYPE_LEASING => [],
            HardwareSale::USAGE_TYPE_COLO => [],
            HardwareSale::USAGE_TYPE_RENT => [],
        ];
        foreach ($model->hardwareSales as $sale) {
            $salesByType[$sale->usage_type][] = $sale;
        }

        foreach ($salesByType as $usageType => $sales) {
            $html .= ArraySpoiler::widget([
                'data' => Sort::by($sales, function (HardwareSale $sale) {
                    $order = ['CHASSIS', 'MOTHERBOARD', 'CPU', 'RAM', 'HDD', 'SSD'];
                    $type = substr($sale->part, 0, strpos($sale->part, ':'));
                    $key = array_search($type, $order, true);
                    if ($key !== false) {
                        return $key;
                    }

                    return INF;
                }),
                'delimiter' => '<br/>',
                'visibleCount' => 0,
                'button' => [
                    'label' => (function () use ($usageType, $sales) {
                        if ($usageType === HardwareSale::USAGE_TYPE_LEASING) {
                            /** @var \DateTime $maxLeasingDate */
                            $maxLeasingDate = array_reduce($sales, function (\DateTime $max, HardwareSale $item) {
                                $date = $item->saleTime();

                                return $date > $max ? $date : $max;
                            }, new \DateTime());

                            return Yii::t('hipanel:server', $usageType) . ' ' . \count($sales)
                                . '<br />' . $maxLeasingDate->format('d.m.Y');
                        }

                        return Yii::t('hipanel:server', $usageType) . ' ' . \count($sales);
                    })(),
                    'tag' => 'button',
                    'type' => 'button',
                    'class' => "btn btn-xs {$badgeColors[$usageType]}",
                    'popoverOptions' => [
                        'html' => true,
                        'placement' => 'bottom',
                        'title' => Yii::t('hipanel:server', 'Parts') . ' '
                            . Html::a(
                                Yii::t('hipanel:server', 'To new tab {icon}', ['icon' => '<i class="fa fa-external-link"></i>']),
                                Url::toSearch('part', ['dst_name_in' => $model->name]),
                                ['class' => 'pull-right', 'target' => '_blank']
                            ),
                        'template' => '
                            <div class="popover" role="tooltip">
                                <div class="arrow"></div>
                                <h3 class="popover-title"></h3>
                                <div class="popover-content" style="height: 25rem; overflow-x: scroll;"></div>
                            </div>
                        ',
                    ],
                ],
                'formatter' => function (HardwareSale $item) {
                    $additionalInfo = null;
                    $title = Html::encode($item->part);
                    if (isset($item->serialno)) {
                        $title .= ': ' . Html::encode($item->serialno);
                    }

                    if (isset($item->leasing_till)) {
                        $additionalInfo = Yii::t('hipanel:server', '{since} &mdash; {till}', [
                            'since' => Yii::$app->formatter->asDate($item->leasing_since, 'short'),
                            'till' => Yii::$app->formatter->asDate($item->leasing_till, 'short'),
                        ]);
                    }

                    if (isset($item->sale_time)) {
                        $additionalInfo = Yii::t('hipanel:server', 'since {date}', [
                            'date' => Yii::$app->formatter->asDate($item->sale_time, 'short'),
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
