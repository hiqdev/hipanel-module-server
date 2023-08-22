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

use hipanel\modules\finance\helpers\ConsumptionConfigurator;
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
            'short' => $user->can('support') ? [
                'label' => Yii::t('hipanel:server', 'short'),
                'columns' => array_filter([
                    'checkbox',
                    $hostingExists ? 'ips' : null,
                    'client', 'dc', 'actions', 'server',
                    $user->can('order.read') ? 'order_no' : null,
                    'hwsummary',
                ]),
            ] : null,
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => array_filter([
                    'checkbox',
                    'actions',
                    'server', 'client_like', 'seller_id',
                    $hostingExists ? 'ips' : null,
                    'tariff_and_discount', 'hwsummary',
                ]),
            ],
            'hardware' => $user->can('part.read') ? [
                'label' => Yii::t('hipanel:server', 'hardware'),
                'columns' => [
                    'checkbox',
                    'rack', 'client', 'dc', 'actions', 'server', 'hwsummary', 'hwcomment',
                ],
            ] : null,
            'manager' => $user->can('manage') ? [
                'label' => Yii::t('hipanel:server', 'manager'),
                'columns' => array_filter([
                    'checkbox',
                    'client_like',
                    'rack', 'actions', 'server', 'tariff',
                    'hwsummary', 'hwcomment',
                    $hostingExists ? 'nums': null,
                ]),
            ] : null,
            'billing' => $user->can('consumption.read') && $user->can('manage') ? [
                'label' => Yii::t('hipanel:server', 'billing'),
                'columns' => [
                    'checkbox',
                    'rack',
                    'actions',
                    'server',
                    'client_like',
                    'tariff',
                    'monthly_fee',
                    'traffic',
                    'additional_services',
                    'type_of_sale',
                    'hwsummary',
                ],
            ] : null,
            'admin' => $user->can('support') ? [
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
                    ...$consumptionConfigurator->getColumns('server'),
                ],
            ] : null,
        ]);
    }
}
