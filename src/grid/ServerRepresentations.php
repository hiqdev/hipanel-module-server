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
            'short' => Yii::$app->user->can('support') ? [
                'label' => Yii::t('hipanel:server', 'short'),
                'columns' => [
                    'checkbox',
                    'ips', 'client', 'dc', 'actions', 'server', 'order_no',
                ],
            ] : null,
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
            'hardware' => Yii::$app->user->can('support') ? [
                'label' => Yii::t('hipanel:server', 'hardware'),
                'columns' => [
                    'checkbox',
                    'rack', 'client', 'dc', 'actions', 'server', 'hwsummary',
                ],
            ] : null,
            'manager' => Yii::$app->user->can('manage') ? [
                'label' => Yii::t('hipanel:server', 'manager'),
                'columns' => [
                    'checkbox',
                    'client_like',
                    'rack', 'actions', 'server', 'tariff',
                    'hwsummary', 'nums',
                ],
            ] : null,
            'billing' => Yii::$app->user->can('consumption.read') && Yii::$app->user->can('manage') ? [
                'label' => Yii::t('hipanel:server', 'billing'),
                'columns' => [
                    'checkbox',
                    'rack',
                    'actions',
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
                    'dc', 'actions', 'server', 'type',
                    'net', 'kvm', 'ipmi', 'pdu', 'ip', 'mac',
                ],
            ] : null,
        ]);
    }
}
