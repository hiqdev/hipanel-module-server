<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\menus;

use hipanel\modules\server\Module;
use hiqdev\yii2\menus\Menu;
use Yii;

class SidebarMenu extends Menu
{
    public function items()
    {
        $app = Yii::$app;
        /** @var Module $module */
        $module = $app->getModule('server');
        $outsideUrlExists = (bool)Yii::$app->params['module.server.order.redirect.url'];
        /** @var User $user */
        $user = $app->user;

        return [
            'servers' => [
                'label'     => Yii::t('hipanel:server', 'Servers'),
                'url'       => ['/server/server/index'],
                'icon'      => 'fa-server',
                'visible'   => $user->can('server.read'),
                'items' => [
                    'servers' => [
                        'label' => Yii::t('hipanel:server', 'Servers'),
                        'url'   => ['/server/server/index'],
                    ],
                    'switch' => [
                        'label'   => Yii::t('hipanel:server', 'Switches'),
                        'url'     => ['/server/hub/index'],
                        'visible' => $user->can('hub.read'),
                    ],
                    'buy-server' => [
                        'label'   => Yii::t('hipanel:server:order', 'Order server'),
                        'url'     => ['/server/order/index'],
                        'visible' => $user->can('server.pay') && $module->orderIsAllowed && $outsideUrlExists,
                    ],
                    'pre-order' => [
                        'label'   => Yii::t('hipanel:server', 'Pre-orders'),
                        'url'     => ['/server/pre-order/index'],
                        'visible' => $user->can('resell') && $module->orderIsAllowed && $outsideUrlExists,
                    ],
                    'refuse' => [
                        'label'   => Yii::t('hipanel:server', 'Refuses'),
                        'url'     => ['/server/refuse/index'],
                        'visible' => $user->can('resell') && $module->orderIsAllowed && $outsideUrlExists,
                    ],
                    'config' => [
                        'label'   => Yii::t('hipanel:server:config', 'Config'),
                        'url'     => ['/server/config/index'],
                        'visible' => $user->can('config.read'),
                    ],
                    'server-resource' => [
                        'label'   => Yii::t('hipanel:server', 'Server resources'),
                        'url'     => ['/resource/servers'],
                        'visible' => $user->can('test.beta'),
                    ],
                ],
            ],
        ];
    }
}
