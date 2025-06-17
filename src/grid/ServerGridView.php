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
use hipanel\components\User;
use hipanel\grid\BoxedGridView;
use hipanel\grid\DataColumn;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\StringHelper;
use hipanel\helpers\Url;
use hipanel\modules\finance\helpers\ConsumptionConfigurator\ConsumptionConfigurator;
use hipanel\modules\finance\helpers\ResourceHelper;
use hipanel\modules\finance\models\Sale;
use hipanel\modules\finance\providers\BillTypesProvider;
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
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ServerGridView extends BoxedGridView
{
    use ColorizeGrid;

    public string $controllerUrl = '@server';
    public array $osImages = [];
    public static array $trafficColumns = [
        'monthly,server_traf_max',
        'overuse,server_traf_max',
        'monthly,server_traf95_max',
        'overuse,server_traf95_max',
        'monthly,support_time',
        'overuse,support_time',
        'monthly,backup_du',
        'overuse,backup_du',
        'monthly,win_license',
    ];
    private const HIDE_UNSALE = false;
    private User $user;
    private ?array $gridColumns = null;

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
        $canReadFinancial = Yii::$app->user->can('server.read-financial-info');
        $canReadSystem = Yii::$app->user->can('server.read-system-info');
        $columns = $this->buildGridColumns();
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
                'visible' => $canReadSystem || $canReadFinancial,
                'value' => function (Server $model): string {
                    $html[] = State::widget(compact('model'));
                    if (!empty($model->status_time)) {
                        $html[] = Yii::t('hipanel:server', 'since {date}', ['date' => $this->formatter->asDate($model->status_time)]);
                    }

                    return Html::tag(
                        'div',
                        implode(" ", $html),
                        ['style' => 'white-space: nowrap; display: inline-flex; gap: 1rem; align-items: center;']
                    );
                },
            ],
            'panel' => [
                'attribute' => 'panel',
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-uppercase'],
                'value' => function ($model) use ($canReadSystem) {
                    $value = $model->getPanel() ? Yii::t('hipanel:server:panel', $model->getPanel()) : Yii::t('hipanel:server:panel',
                        'No control panel');
                    if ($canReadSystem) {
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
                'visible' => $canReadFinancial,
                'value' => fn($model) => Expires::widget(compact('model')),
            ],
            'tariff' => [
                'format' => 'raw',
                'filterAttribute' => 'tariff_like',
                'value' => fn($model) => $this->formatTariff($model),
                'exportedValue' => fn($model) => end($model->sales)->tariff ?? '',
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
                'value' => fn($model) => $this->formatTariffWithoutUnsale($model),
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
                'value' => fn($model) => Html::tag('span', Html::encode($model->type_label), ['class' => 'label label-default']),
                'contentOptions' => fn($model) => GridLegend::create($this->findOrFailGridLegend($model))->gridColumnOptions('actions'),
            ],
            'export_switch_inn' => [
                'label' => Yii::t('hipanel:server', 'Switch INN'),
                'value' => function (Server $server): ?string {
                    $binding = $server->bindings['rack'] ?? null;

                    return $binding ? $binding->switch_inn : '';
                },
            ],
            'export_rack_name' => [
                'label' => Yii::t('hipanel:server', 'Rack name'),
                'value' => function (Server $server): ?string {
                    $binding = $server->bindings['rack'] ?? null;

                    if ($binding) {
                        return $binding->switch . ($binding->port ? ':' . $binding->port : '');
                    }

                    return '';
                },
            ],
            'export_rack_description' => [
                'label' => Yii::t('hipanel:server', 'Rack descriptions'),
                'value' => function (Server $server): ?string {
                    $binding = $server->bindings['rack'] ?? null;

                    return $binding ? $binding->switch_label : '';
                },
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
                'headerOptions' => ['style' => 'white-space:nowrap;'],
                'value' => fn($model) => $this->getMonthlyFee($model),
                'visible' => Yii::$app->user->can('consumption.read'),
            ],
            'type_of_sale' => [
                'label' => Yii::t('hipanel:server', 'Type of sale'),
                'format' => 'raw',
                'filter' => false,
                'headerOptions' => ['style' => 'white-space:nowrap;'],
                'value' => fn(Server $model) => $this->getTypeOfSale($model),
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
        ], $this->getConsumptionColumns(), $columns);
    }

    private function buildGridColumns(): array
    {
        if ($this->gridColumns === null) {
            $consumptionConfigurator = Yii::$container->get(ConsumptionConfigurator::class);
            $consumptionColumns = $consumptionConfigurator->getColumnsWithLabels('server');
            $this->gridColumns = ResourceHelper::buildGridColumns($consumptionColumns);
        }

        return $this->gridColumns;
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
                'class' => 'tariff-chain ' . ($this->user->can('server.read-financial-info') ?: 'inactiveLink'),
                'style' => 'margin: 0; padding: 0;',
            ]);

            $html = Html::tag('li',
                Html::a($sale['start'] . ' - ' . $sale['finish'], ['@sale/view', 'id' => $sale['id']])
            );

            $result .= Html::tag('ul', $html, [
                'class' => 'tariff-chain ' . ($this->user->can('server.read-financial-info') ?: 'inactiveLink'),
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

    /**
     * @param Consumption|null $consumption
     * @return array
     * @psalm-return array{included: mixed, price: string}
     * @throws \yii\base\InvalidConfigException
     */
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

    private function getMonthlyFee(Server $model): string
    {
        $unionConsumption = new Consumption();
        $prices = [];
        if ($model->consumptions) {
            foreach ($model->consumptions as $consumption) {
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
            }
        }
        $unionConsumption->setAttribute('prices', $prices);

        return $unionConsumption->getFormattedPrice();
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

    private function getConsumptionColumns(): array
    {
        $billTypes = Yii::$container->get(BillTypesProvider::class)->getTypesList();
        $columns = [];
        foreach (self::$trafficColumns as $columnName) {
            $label = $billTypes[$columnName] ?? '';
            $label = match (true) {
                str_contains($label, 'monthly') || str_contains($columnName, 'monthly') => $label,
                default => $label,
            };
            $columns[$columnName] = [
                'class' => DataColumn::class,
                'label' => str_contains($label, 'monthly') || str_contains($columnName, 'monthly') ? $label : $label . " overuse cost",
                'format' => 'raw',
                'filter' => false,
                'headerOptions' => ['style' => 'white-space: nowrap;'],
                'value' => function (Server $model) use ($columnName): string {
                    $limitAndPrice = $this->getIncludedAndPrice($model->consumptions[$columnName] ?? null);

                    return $this->formatIncludedAndPrice($limitAndPrice);
                },
                'visible' => Yii::$app->user->can('consumption.read'),
                'exportedValue' => function (Server $model) use ($columnName): ?string {
                    $limitAndPrice = $this->getIncludedAndPrice($model->consumptions[$columnName] ?? null);

                    return $limitAndPrice['price'] ?? null;
                },
            ];
            if (str_contains($columnName, 'overuse')) {
                $extraColumnName = $columnName . ',included';
                $columns[$columnName]['exportedColumns'] = [$extraColumnName, $columnName];
                if (str_contains($label, 'included')) {
                    $extraColumnLabel = str_replace(' overuse', ' included', $label);
                } else {
                    $extraColumnLabel = $label . ' included';
                }
                $columns[$extraColumnName] = [
                    'class' => DataColumn::class,
                    'label' => $extraColumnLabel,
                    'format' => 'raw',
                    'filter' => false,
                    'value' => function (Server $model) use ($columnName): ?string {
                        $limitAndPrice = $this->getIncludedAndPrice($model->consumptions[$columnName] ?? null);

                        return $limitAndPrice['included'] ?? null;
                    },
                    'visible' => Yii::$app->user->can('consumption.read'),
                ];
            }
        }

        return $columns;
    }
}
