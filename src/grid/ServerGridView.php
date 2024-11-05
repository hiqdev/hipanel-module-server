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

use DateTime;
use hipanel\base\Model;
use hipanel\components\User;
use hipanel\grid\BoxedGridView;
use hipanel\grid\DataColumn;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\StringHelper;
use hipanel\helpers\Url;
use hipanel\modules\finance\helpers\ConsumptionConfigurator;
use hipanel\modules\finance\helpers\ResourceHelper;
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
use yii\db\ActiveRecordInterface;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ServerGridView extends BoxedGridView
{
    use ColorizeGrid;

    public string $controllerUrl = '@server';
    public array $osImages = [];
    public static array $trafficColumns = [
        'monthly,server_traf_max' => 'Server traffic monthly fee',
        'overuse,server_traf_max' => 'Server traffic overuse',
        'monthly,server_traf95_max' => 'Server traffic 95% monthly fee',
        'overuse,server_traf95_max' => 'Server traffic 95% overuse',
    ];
    private const HIDE_UNSALE = false;
    private User $user;

    public function init()
    {
        parent::init();
        $this->user = Yii::$app->user;
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

    public function columns()
    {
        $canSupport = Yii::$app->user->can('support');
        $consumptionConfigurator = Yii::$container->get(ConsumptionConfigurator::class);
        $consumptionColumns = $consumptionConfigurator->getColumnsWithLabels('server');
        $columns = ResourceHelper::buildGridColumns($consumptionColumns);
        $user = Yii::$app->user;

        return array_merge(parent::columns(), [
            'server' => [
                'class' => ServerNameColumn::class,
                'exportedColumns' => array_filter([
                    'tags',
                    'export_name',
                    'export_note',
                    $user->can('server.see-label') && $user->can('owner-staff') ? 'export_internal_note' : null,
                ]),
            ],
            'export_name' => [
                'label' => Yii::t('hipanel', 'Name'),
                'value' => static fn($server): string => $server->name ?? '',
            ],
            'export_note' => [
                'label' => Yii::t('hipanel', 'Note'),
                'value' => static fn($server): string => $server->note ?? '',
            ],
            'export_internal_note' => [
                'label' => Yii::t('hipanel', 'Internal Note'),
                'value' => static fn($server): string => $server->label ?? '',
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
                    if (isset($model->status_time)) {
                        $html .= ' ' . Html::tag('nobr',
                                Yii::t('hipanel:server', 'since {date}', ['date' => Yii::$app->formatter->asDate($model->status_time)]));
                    }

                    return $html;
                },
            ],
            'panel' => [
                'attribute' => 'panel',
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-uppercase'],
                'value' => function ($model) use ($canSupport) {
                    $value = $model->getPanel() ? Yii::t('hipanel:server:panel', $model->getPanel()) : Yii::t('hipanel:server:panel',
                        'No control panel');
                    if ($canSupport) {
                        $value .= $model->wizzarded ? Label::widget([
                            'label' => 'W',
                            'tag' => 'sup',
                            'color' => 'success',
                        ]) : '';
                    }

                    return $value;
                },
            ],
            'os' => [
                'attribute' => 'os',
                'format' => 'raw',
                'filter' => false,
                'enableSorting' => false,
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
            'tariff_without_unsale' => [
                'format' => 'raw',
                'value' => function ($model) {
                    return $this->formatTariffWithoutUnsale($model);
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
                                return Html::a($ip,
                                    IpController::getSearchUrl(['server_in' => Html::encode($model->name)]),
                                    [
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
                'visible' => Yii::$app->user->can('server.see-label') && Yii::$app->user->can('owner-staff'),
            ],
            'type' => [
                'class' => RefColumn::class,
                'format' => 'raw',
                'filter' => false,
                'i18nDictionary' => 'hipanel:server',
                'value' => function ($model) {
                    return Html::tag('span', Html::encode($model->type_label), ['class' => 'label label-default']);
                },
            ],
            'detailed_type' => [
                'class' => RefColumn::class,
                'label' => Yii::t('hipanel', 'Type'),
                'format' => 'raw',
                'filter' => false,
                'i18nDictionary' => 'hipanel:server',
                'value' => function ($model) {
                    return Html::tag('span', Html::encode($model->type_label), ['class' => 'label label-default']);
                },
                'contentOptions' => function ($model) {
                    return GridLegend::create($this->findOrFailGridLegend($model))->gridColumnOptions('actions');
                },
            ],
            'export_switch_inn' => [
                'label' => Yii::t('hipanel:server', 'Switch INN'),
                'value' => fn(Server $server): ?string => $server->bindings['rack']->switch_inn,
            ],
            'export_rack_name' => [
                'label' => Yii::t('hipanel:server', 'Rack name'),
                'value' => function (Server $server): ?string {
                    $binding = $server->bindings['rack'];

                    return $binding->switch . ($binding->port ? ':' . $binding->port : '');
                },
            ],
            'export_rack_description' => [
                'label' => Yii::t('hipanel:server', 'Rack descriptions'),
                'value' => fn(Server $server): ?string => $server->bindings['rack']->switch_label,
            ],
            'rack' => [
                'class' => BindingColumn::class,
                'enableSorting' => false,
                'exportedColumns' => [
                    'export_switch_inn',
                    'export_rack_name',
                    'export_rack_description',
                ],
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
            'jbod' => [
                'class' => BindingColumn::class,
            ],
            'nums' => [
                'label' => '',
                'format' => 'raw',
                'value' => function ($model) {
                    $ips_num = $model->ips_num;
                    $ips = $ips_num ? Html::a("$ips_num ips",
                        IpController::getSearchUrl(['server' => $model->name])) : 'no ips';
                    $act_acs_num = $model->acs_num - $model->del_acs_num;
                    $del_acs_num = $model->del_acs_num;
                    $acs_num = $act_acs_num . ($del_acs_num ? "+$del_acs_num" : '');
                    $acs = $acs_num ? Html::a("$acs_num acc",
                        AccountController::getSearchUrl(['server' => $model->name])) : 'no acc';

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
            'hwsummary_auto' => [
                'filter' => false,
                'label' => Yii::t('hipanel:server', 'Auto Hardware Summary'),
            ],
            'hwsummary_diff' => [
                'class' => SummaryDiffColumn::class,
            ],
            'hwcomment' => [
                'filter' => false,
                'label' => Yii::t('hipanel:server', 'Hardware Comment'),
            ],
        ], $this->getTrafficColumns(), $columns);
    }

    protected function formatTariff($model): string
    {
        $html = '';
        $sales = $this->getActiveSales($model);

        foreach ($sales as $sale) {
            if ($sale->tariff) {
                $tariff = Html::encode($sale->tariff);
                $client = Html::encode($sale->seller);
                $tariff = $sale->tariff_id && $this->user->can('plan.read') ? Html::a($tariff, [
                    '@plan/view',
                    'id' => $sale->tariff_id,
                ]) : $tariff;
                $client = $sale->seller && $this->user->can('client.read') ? '(' . Html::a($client, [
                        '@client/view',
                        'id' => $sale->seller_id,
                    ]) . ')' : '';

                $html .= Html::tag('li', $tariff . '&nbsp;' . $client);
            }
        }

        return Html::tag('ul', $html, [
            'class' => 'tariff-chain',
            'style' => 'margin: 0; padding: 0;',
        ]);
    }

    protected function getActiveSales(Server $model)
    {
        $sales = $this->getAndFilterServerSalesByVisibility($model);

        if (empty($sales)) {
            return [];
        }

        foreach ($sales as $sale) {
            if ($sale->time <= date("Y-m-d H:i:s") && ($sale->unsale_time === null || $sale->unsale_time > date("Y-m-d H:i:s"))) {
                $data[] = $sale;
            }
        }

        return $data ?? [];
    }

    protected function getAndFilterServerSalesByVisibility(Server $model): array
    {
        $models = $this->getModelWithUserPermission($model);

        if (empty($models)) {
            return [];
        }

        foreach ($models as $sale) {
            if ($sale->tariff && $this->checkHide($sale)) {
                $sales[] = $sale;
            }
        }

        return $sales ?? [];
    }

    protected function formatTariffWithoutUnsale(Server $server)
    {
        $models = $this->getModelWithUserPermission($server);

        foreach ($models as $model) {
            if ($model->tariff && $this->checkHide($model)) {
                $tariff = Html::encode($model->tariff);
                $data[] = [
                    'tariff' => $model->tariff_id ? '(' . Html::a($tariff, [
                            '@plan/view',
                            'id' => $model->tariff_id,
                        ]) . ')' : $tariff,
                    'client' => $model->seller ? Html::a(Html::encode($model->seller), [
                        '@client/view',
                        'id' => $model->seller_id,
                    ]) : '',
                    'buyer' => $model->buyer ? Html::a(Html::encode($model->buyer), [
                        '@client/view',
                        'id' => $model->buyer_id,
                    ]) : '',
                    'start' => Yii::$app->formatter->asDate($model->time),
                    'finish' => $model->unsale_time ? Yii::$app->formatter->asDate($model->unsale_time) : '',
                    'id' => $model->id,
                ];
            }
        }
        $result = '';

        for ($i = 0; $i < count($data); $i++) {
            $html = '';
            if ($i == 0) {
                $html .= Html::tag('li', $data[$i]['client']);
                $html .= Html::tag('li', $data[$i]['tariff'] . '&nbsp;' . $data[$i]['buyer']);
                if (empty($data[$i]['finish']) && count($data) > 1) {
                    $data[$i]['finish'] = $data[$i + 1]['start'];
                } else {
                    $data[$i]['finish'] = '&#8734;';
                }
            } else {
                $html .= Html::tag('li', $data[0]['client']);
                $html .= Html::tag('li', $data[0]['tariff'] . '&nbsp;' . $data[0]['buyer']);
                $html .= Html::tag('li', $data[$i]['tariff'] . '&nbsp;' . $data[$i]['buyer']);
                if (empty($data[$i]['finish'])) {
                    $data[$i]['finish'] = '&#8734;';
                }
            }
            $result .= Html::tag('ul', $html, [
                'class' => 'tariff-chain ' . ($this->user->can('support') ?: 'inactiveLink'),
                'style' => 'margin: 0; padding: 0;',
            ]);

            $html = Html::tag('li',
                Html::a($sale['start'] . ' - ' . $sale['finish'], ['@sale/view', 'id' => $sale['id']])
            );

            $result .= Html::tag('ul', $html, [
                'class' => 'tariff-chain ' . ($this->user->can('support') ?: 'inactiveLink'),
                'style' => 'margin: 0; padding: 0;',
            ]);
            $result .= Html::tag('br');
        }

        return $result;
    }

    protected function getModelWithUserPermission(ActiveRecordInterface $model)
    {
        $models = [];
        if ($this->user->can('sale.read') && !empty($model->sales)) {
            foreach ($model->sales as $sale) {
                $models[] = $sale;
            }
        } elseif ($this->user->can('plan.read')) {
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

        return $models;
    }

    protected function checkHide(Sale $model)
    {
        $result = true;
        if (self::HIDE_UNSALE) {
            $result = ($model->unsale_time === null || $model->unsale_time > date('Y-m-d H:i:s'));
        }

        return $result;
    }

    /**
     * @param array $limitAndPrice
     * @return string
     */
    private function formatIncludedAndPrice(array $limitAndPrice): string
    {
        $result = [];
        foreach ($limitAndPrice as $name => $value) {
            if ($value) {
                $result[] = Html::tag(
                    'span',
                    sprintf('%s: %s', Html::tag('b', Yii::t('hipanel:server', $name)), $value),
                    ['style' => 'white-space: nowrap;']
                );
            }
        }

        return implode("\n", $result);
    }

    #[ArrayShape(['included' => "mixed", 'price' => "string"])]
    private function getIncludedAndPrice(?Consumption $consumption): array
    {
        if ($consumption === null) {
            return [];
        }
        $widget = Yii::createObject(['class' => ResourceConsumptionTable::class, 'model' => $consumption]);

        return [
            'included' => $widget->getFormatted($consumption, $consumption->limit),
            'price' => $consumption->getFormattedPrice(),
        ];
    }

    private function getMonthlyFee($model): string
    {
        $unionConsumption = new Consumption();
        $prices = [];
        if ($model->consumptions) {
            array_walk($model->consumptions, function (Consumption $consumption) use (&$prices) {
                if ($consumption->type && $consumption->hasFormattedAttributes() && StringHelper::startsWith($consumption->type,
                        'monthly,')) {
                    if ($consumption->price) {
                        $consumption->setAttribute('prices', [$consumption->currency => $consumption->price]);
                    }
                    foreach ($consumption->prices as $currency => $price) {
                        $prices[$currency] ??= 0;
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
            public string $typeLabel;
            public string $value;
            public string $prefix;
        };
        $models = [];
        foreach ([
                     'monthly,support_time',
                     'overuse,support_time',
                     'monthly,backup_du',
                     'overuse,backup_du',
                     'monthly,win_license',
                 ] as $type) {
            if (isset($model->consumptions[$type]) && $model->consumptions[$type]->hasFormattedAttributes()) {
                $consumption = $model->consumptions[$type];
                $prefix = str_starts_with($consumption->type, 'monthly,') ? 'monthly' : 'overuse';
                $v = $this->getIncludedAndPrice($consumption);
                $models[] = new $additional([
                    'typeLabel' => Yii::t('hipanel.server.consumption.type', $consumption->typeLabel),
                    'value' => $this->formatIncludedAndPrice($this->getIncludedAndPrice($consumption)),
                    'prefix' => $prefix,
                ]);
            }
        }


        return GridView::widget([
            'layout' => '{items}',
            'showOnEmpty' => false,
            'emptyText' => '',
            'tableOptions' => ['class' => 'table table-condensed'],
            'headerRowOptions' => [
                'style' => 'display: none;',
            ],
            'dataProvider' => new ArrayDataProvider(['allModels' => $models, 'pagination' => false]),
            'columns' => [
                [
                    'attribute' => 'typeLabel',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-nowrap', 'style' => 'background-color: inherit;'],
                ],
                ['attribute' => 'prefix', 'format' => 'raw', 'contentOptions' => ['class' => 'text-nowrap']],
                ['attribute' => 'value', 'format' => 'raw', 'contentOptions' => ['class' => 'text-nowrap']],
            ],
        ]);
    }

    private function getTypeOfSale(Server $model): string
    {
        $html = '';
        $badgeColors = [
            'installment' => 'bg-orange',
            'rent' => 'bg-purple',
            'sold' => 'bg-olive',
        ];

        if (empty($model->hardwareSales)) {
            return $html;
        }

        $salesByType = [
            HardwareSale::USAGE_TYPE_INSTALLMENT => [],
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
                        if ($usageType === HardwareSale::USAGE_TYPE_INSTALLMENT) {
                            /** @var DateTime $maxInstallmentDate */
                            $maxInstallmentDate = array_reduce($sales, function (DateTime $max, HardwareSale $item) {
                                $date = $item->saleTime();

                                return $date > $max ? $date : $max;
                            }, new DateTime());

                            return Yii::t('hipanel:server', $usageType) . ' ' . count($sales)
                                . '<br />' . $maxInstallmentDate->format('d.m.Y');
                        }

                        return Yii::t('hipanel:server', $usageType) . ' ' . count($sales);
                    })(),
                    'tag' => 'button',
                    'type' => 'button',
                    'class' => "btn btn-xs " . ($badgeColors[$usageType] ?? ''),
                    'popoverOptions' => [
                        'html' => true,
                        'placement' => 'bottom',
                        'title' => Yii::t('hipanel:server', 'Parts') . ' '
                            . Html::a(
                                Yii::t('hipanel:server',
                                    'To new tab {icon}',
                                    ['icon' => '<i class="fa fa-external-link"></i>']),
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

                    if (isset($item->installment_till)) {
                        $additionalInfo = Yii::t('hipanel:server', '{since} &mdash; {till}', [
                            'since' => Yii::$app->formatter->asDate($item->installment_since, 'short'),
                            'till' => Yii::$app->formatter->asDate($item->installment_till, 'short'),
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

    private function getTrafficColumns(): array
    {
        $columns = [];
        foreach (self::$trafficColumns as $columnName => $label) {
            $columns[$columnName] = [
                'class' => DataColumn::class,
                'label' => Yii::t('hipanel:server', $label),
                'format' => 'raw',
                'filter' => false,
                'value' => function ($model) use ($columnName): string {
                    $limitAndPrice = isset($model->consumptions[$columnName]) ? $this->getIncludedAndPrice($model->consumptions[$columnName]) : [];

                    return $this->formatIncludedAndPrice($limitAndPrice);
                },
                'visible' => Yii::$app->user->can('consumption.read'),
                'exportedValue' => fn($model) => $this->getIncludedAndPrice($model->consumptions[$columnName])['price'],
            ];
            if (str_contains($columnName, 'overuse')) {
                $extraColumnName = $columnName . ',included';
                $columns[$columnName]['exportedColumns'] = [$extraColumnName, $columnName];
                $columns[$extraColumnName] = [
                    'class' => DataColumn::class,
                    'label' => Yii::t('hipanel:server', str_replace(' overuse', ' included', $label)),
                    'format' => 'raw',
                    'filter' => false,
                    'value' => function ($model) use ($columnName): ?string {
                        $limitAndPrice = isset($model->consumptions[$columnName]) ? $this->getIncludedAndPrice($model->consumptions[$columnName]) : [];

                        return $limitAndPrice['included'] ?? null;
                    },
                    'visible' => Yii::$app->user->can('consumption.read'),
                ];
            }
        }

        return $columns;
    }
}
