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

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class ServerRepresentations extends RepresentationCollection
{
    protected function fillRepresentations(): void
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'server', 'client_like', 'seller_id',
                    'ips', 'state', 'expires',
                    'tariff_and_discount',
                ],
            ],
            'short' => Yii::$app->user->can('support') ? [
                'label' => Yii::t('hipanel:server', 'short'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'ips', 'client', 'dc', 'server', 'order_no',
                ],
            ] : null,
            'hardware' => Yii::$app->user->can('support') ? [
                'label' => Yii::t('hipanel:server', 'hardware'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'rack', 'client', 'dc', 'server', 'hwsummary',
                ],
            ] : null,
            'manager' => Yii::$app->user->can('manage') ? [
                'label' => Yii::t('hipanel:server', 'manager'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'client_like',
                    'rack', 'server', 'tariff',
                    'hwsummary', 'nums',
                ],
            ] : null,
            'billing' => Yii::$app->user->can('consumption.read') && Yii::$app->user->can('manage') ? [
                'label' => Yii::t('hipanel:server', 'billing'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'rack',
                    'server',
                    'client_like',
                    'monthly_fee',
                    'traffic',
                    'additional_services',
                    'type_of_sale',
                ],
            ] : null,
            'admin' => Yii::$app->user->can('support') ? [
                'label' => Yii::t('hipanel:server', 'admin'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'dc', 'server', 'type',
                    'net', 'kvm', 'ipmi', 'pdu', 'ip', 'mac',
                ],
            ] : null,
        ]);
    }
}
