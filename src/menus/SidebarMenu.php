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

use hipanel\modules\server\Module;
use Yii;

class SidebarMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        $app = Yii::$app;
        /** @var Module $module */
        $module = $app->getModule('server');

        return [
            'servers' => [
                'label'     => Yii::t('hipanel:server', 'Servers'),
                'url'       => ['/server/server/index'],
                'icon'      => 'fa-server',
                'visible'   => $app->user->can('server.read'),
                'items' => [
                    'servers' => [
                        'label' => Yii::t('hipanel:server', 'Servers'),
                        'url'   => ['/server/server/index'],
                    ],
                    'switch' => [
                        'label'   => Yii::t('hipanel:server', 'Switches'),
                        'url'     => ['/server/hub/index'],
                        'visible' => $app->user->can('hub.read'),
                    ],
                    'buy-server' => [
                        'label'   => Yii::t('hipanel:server:order', 'Order server'),
                        'url'     => ['/server/order/index'],
                        'visible' => $app->user->can('deposit') && $module->orderIsAllowed,
                    ],
                    'pre-order' => [
                        'label'   => Yii::t('hipanel:server', 'Pre-orders'),
                        'url'     => ['/server/pre-order/index'],
                        'visible' => $app->user->can('resell') && $module->orderIsAllowed,
                    ],
                    'refuse' => [
                        'label'   => Yii::t('hipanel:server', 'Refuses'),
                        'url'     => ['/server/refuse/index'],
                        'visible' => $app->user->can('resell') && $module->orderIsAllowed,
                    ],
                ],
            ],
        ];
    }
}
