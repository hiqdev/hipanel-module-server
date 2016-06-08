<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server;

use Yii;

class SidebarMenu extends \hipanel\base\Menu implements \yii\base\BootstrapInterface
{
    protected $_addTo = 'sidebar';

    protected $_where = [
        'after'     => ['domains', 'tickets', 'finance', 'clients', 'dashboard'],
        'before'    => ['hosting'],
    ];

    public function items()
    {
        return [
            'servers' => [
                'label' => Yii::t('hipanel/server', 'Servers'),
                'url'   => ['/server/server/index'],
                'icon'  => 'fa-server',
                'items' => [
                    'servers' => [
                        'label' => Yii::t('hipanel/server', 'Servers'),
                        'url'   => ['/server/server/index'],
                    ],
                    'buy-server' => [
                        'label' => Yii::t('hipanel/server/order', 'Buy server'),
                        'url'   => ['/server/order/index'],
                    ],
                    'pre-order' => [
                        'label'   => Yii::t('hipanel/server', 'Pre-orders'),
                        'url'     => ['/server/pre-order/index'],
                        'visible' => function () { return Yii::$app->user->can('resell') ?: false; },
                    ],
                    'refuse' => [
                        'label'   => Yii::t('hipanel/server', 'Refuses'),
                        'url'     => ['/server/refuse/index'],
                        'visible' => function () { return Yii::$app->user->can('resell') ?: false; },
                    ],
                ],
            ],
        ];
    }
}
