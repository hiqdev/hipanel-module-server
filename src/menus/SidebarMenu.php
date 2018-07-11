<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\menus;

use Yii;

class SidebarMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        return [
            'servers' => [
                'label'     => Yii::t('hipanel:server', 'Servers'),
                'url'       => ['/server/server/index'],
                'icon'      => 'fa-server',
                'visible'   => Yii::$app->user->can('server.read'),
                'items' => [
                    'servers' => [
                        'label' => Yii::t('hipanel:server', 'Servers'),
                        'url'   => ['/server/server/index'],
                    ],
                    'switch' => [
                        'label'   => Yii::t('hipanel:server', 'Switches'),
                        'url'     => ['/server/hub/index'],
                        'visible' => Yii::$app->user->can('admin'),
                    ],
                    'buy-server' => [
                        'label'   => Yii::t('hipanel:server:order', 'Order server'),
                        'url'     => ['/server/order/index'],
                        'visible' => Yii::$app->user->can('deposit'),
                    ],
                    'pre-order' => [
                        'label'   => Yii::t('hipanel:server', 'Pre-orders'),
                        'url'     => ['/server/pre-order/index'],
                        'visible' => Yii::$app->user->can('resell'),
                    ],
                    'refuse' => [
                        'label'   => Yii::t('hipanel:server', 'Refuses'),
                        'url'     => ['/server/refuse/index'],
                        'visible' => Yii::$app->user->can('resell'),
                    ],
                ],
            ],
        ];
    }
}
