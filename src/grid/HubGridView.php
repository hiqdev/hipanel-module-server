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

use hipanel\grid\BoxedGridView;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\modules\client\grid\ClientColumn;
use hipanel\modules\finance\module\ConsumptionConfiguration\Application\ConsumptionConfigurator;
use hipanel\modules\finance\helpers\ResourceHelper;
use hipanel\modules\server\menus\HubActionsMenu;
use hipanel\modules\server\models\Hub;
use hipanel\widgets\gridLegend\ColorizeGrid;
use hipanel\widgets\State;
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\db\ActiveRecordInterface;
use yii\helpers\Html;

class HubGridView extends BoxedGridView
{
    use ColorizeGrid;

    /**
     * @var array
     */
    public $extraOptions = [];

    public function columns()
    {
        $consumptionConfigurator = Yii::$container->get(ConsumptionConfigurator::class);
        $consumptionColumns = $consumptionConfigurator->getColumnsWithLabels('switch');
        $columns = ResourceHelper::buildGridColumns($consumptionColumns, date("Y-m"));
        $extraColumns = [];
        foreach (['snmp_version_id', 'digit_capacity_id', 'nic_media'] as $attribute) {
            $extraColumns[$attribute] = [
                'attribute' => $attribute,
                'enableSorting' => false,
                'value' => function (Hub $hub) use ($attribute): ?string {
                    if (isset($this->extraOptions[$attribute]) && $hub->{$attribute}) {
                        return $this->extraOptions[$attribute][$hub->{$attribute}];
                    }

                    return null;
                },
            ];
        }

        return array_merge(parent::columns(), $extraColumns, [
            'inn' => [
                'enableSorting' => false,
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'inn_ilike',
            ],
            'model' => [
                'enableSorting' => false,
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'model_ilike',
            ],
            'ip' => [
                'enableSorting' => false,
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'ip_ilike',
            ],
            'mac' => [
                'enableSorting' => false,
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'mac_ilike',
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => HubActionsMenu::class,
            ],
            'traf_server_id' => [
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function ($model) {
                    return Html::a(Html::encode($model->traf_server_id_label), ['@server/view', 'id' => $model->traf_server_id]);
                },
            ],
            'vlan_server_id' => [
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function ($model) {
                    return Html::a(Html::encode($model->vlan_server_id_label), ['@server/view', 'id' => $model->vlan_server_id]);
                },
            ],
            'buyer' => [
                'label' => Yii::t('hipanel:server:hub', 'Buyer'),
                'class' => ClientColumn::class,
                'idAttribute' => 'buyer_id',
                'attribute' => 'buyer_id',
                'nameAttribute' => 'buyer',
                'enableSorting' => false,
            ],
            'switch' => [
                'class' => MainColumn::class,
                'attribute' => 'name',
                'filterAttribute' => 'name_ilike',
                'note' => 'note',
                'exportedColumns' => ['tags', 'switch'],
            ],
            'state_label' => [
                'attribute' => 'state_label',
                'label' => Yii::t('hipanel:server:hub', 'State'),
                'filter' => false,
                'format' => 'raw',
                'value' => static fn(ActiveRecordInterface $model): string => State::widget(['model' => $model]),
            ],
            'type' => [
                'class' => RefColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
                'enableSorting' => false,
                'findOptions' => [
                    'select' => 'full',
                    'mapOptions' => ['from' => 'id', 'to' => 'label'],
                ],
                'attribute' => 'type_id',
                'i18nDictionary' => 'hipanel:server:hub',
                'gtype' => 'type,device,switch',
                'value' => function ($model) {
                    return Yii::t('hipanel:server:hub', Html::encode($model->type_label));
                },
            ],
            'type_label' => [
                'class' => RefColumn::class,
                'filter' => false,
                'format' => 'raw',
                'i18nDictionary' => 'hipanel:server:hub',
                'value' => function ($model) {
                    return Html::encode($model->type_label);
                },
            ],
            'server_type' => [
                'class' => RefColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
                'enableSorting' => false,
                'findOptions' => [
                    'select' => 'full',
                    'mapOptions' => ['from' => 'id', 'to' => 'label'],
                ],
                'attribute' => 'server_type_id',
                'i18nDictionary' => 'hipanel:server',
                'gtype' => 'type,device,server',
                'value' => function ($model) {
                    return Yii::t('hipanel:server', Html::encode($model->server_type_label));
                },
            ],
            'server_type_label' => [
                'class' => RefColumn::class,
                'filter' => false,
                'format' => 'raw',
                'i18nDictionary' => 'hipanel:server',
                'value' => function ($model) {
                    return Html::encode($model->server_type_label);
                },
            ],
            'tariff' => [
                'format' => 'raw',
                'filterAttribute' => 'tariff_ilike',
                'value' => function (Hub $model): ?string {
                    $tariff = Html::encode($model->tariff);

                    return Yii::$app->user->can('plan.read')
                        ? Html::a($tariff, ['@plan/view', 'id' => $model->tariff_id])
                        : $tariff;
                },
            ],
            'sale_time' => [
                'attribute' => 'sale_time',
                'format' => 'datetime',
            ],
            'order_no' => [
                'attribute' => 'order_no',
                'filterAttribute' => 'order_no_ilike',
                'enableSorting' => false,
            ],
            'login' => [
                'visible' => Yii::$app->user->can('server.manage-settings'),
            ],
            'password' => [
                'visible' => Yii::$app->user->can('server.manage-settings'),
            ],
        ], $columns);
    }
}
