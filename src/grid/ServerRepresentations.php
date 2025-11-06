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

use hipanel\modules\finance\module\ConsumptionConfiguration\Application\ConsumptionConfigurator;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class ServerRepresentations extends RepresentationCollection
{
    protected function fillRepresentations(): void
    {
        $hostingExists = class_exists(\hipanel\modules\hosting\Module::class);
        $user = Yii::$app->user;
        $consumptionConfigurator = Yii::$container->get(ConsumptionConfigurator::class);
        $this->representations = array_filter([
            'short' => ($user->can('server.read-financial-info') || $user->can('server.read-system-info')) ? [
                'label' => Yii::t('hipanel:server', 'short'),
                'columns' => array_filter([
                    'checkbox',
                    $hostingExists ? 'ips' : null,
                    'last_client', 'dc', 'actions', 'server', 'state',
                    $user->can('order.read') && $user->can('owner-staff') ? 'order_no' : null,
                    'hwsummary',
                ]),
            ] : null,
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => array_filter([
                    'checkbox',
                    'actions',
                    'server', 'state', 'last_client', 'seller_id',
                    $hostingExists ? 'ips' : null,
                    'tariff_and_discount', 'hwsummary',
                ]),
            ],
            'hardware' => $user->can('part.read') ? [
                'label' => Yii::t('hipanel:server', 'hardware'),
                'columns' => [
                    'checkbox',
                    'rack', 'last_client', 'dc', 'actions', 'server', 'hwsummary', 'hwcomment',
                ],
            ] : null,
            'summary' => $user->can('owner-staff') ? [
                'label' => Yii::t('hipanel:server', 'hwsummary'),
                'columns' => [
                    'checkbox',
                    'server', 'hwsummary_auto', 'hwsummary_diff'
                ],
            ] : null,
            'manager' => $user->can('server.read-billing') ? [
                'label' => Yii::t('hipanel:server', 'manager'),
                'columns' => array_filter([
                    'checkbox',
                    'last_client',
                    'rack', 'actions', 'server', 'tariff',
                    'hwsummary', 'hwcomment',
                    $hostingExists ? 'nums': null,
                ]),
            ] : null,
            'billing' => $user->can('consumption.read') && $user->can('server.read-billing') ? [
                'label' => Yii::t('hipanel:server', 'billing'),
                'columns' => [
                    'checkbox',
                    'rack',
                    'actions',
                    'server',
                    'last_client',
                    'tariff',
                    'monthly_fee',
                    ...ServerGridView::$trafficColumns,
                    'additional_services',
                    'type_of_sale',
                    'hwsummary',
                ],
            ] : null,
            'admin' => $user->can('server.read-system-info') ? [
                'label' => Yii::t('hipanel:server', 'admin'),
                'columns' => [
                    'checkbox',
                    'dc', 'actions', 'server', 'type',
                    'net', 'kvm', 'ipmi', 'pdu', 'jbod', 'ip', 'mac', 'hwsummary',
                ],
            ] : null,
            'consumption' => $user->can('consumption.read') ? [
                'label' => Yii::t('hipanel:server', 'consumption'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'type',
                    'dc',
                    'server',
                    ...$consumptionConfigurator->getColumns('server')->names(),
                ],
            ] : null,
        ]);
    }
}
