<?php

declare(strict_types=1);
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\menus;

use hipanel\components\User;
use hiqdev\yii2\menus\Menu;
use Yii;

class SidebarMenu extends Menu
{
    public function items()
    {
        $app = Yii::$app;
        /** @var User $user */
        $user = $app->user;

        return [
            'servers' => [
                'label' => Yii::t('hipanel:server', 'Servers'),
                'url' => ['/server/server/index'],
                'icon' => 'fa-server',
                'visible' => $user->can('server.read'),
                'items' => [
                    'servers' => [
                        'label' => Yii::t('hipanel:server', 'Servers'),
                        'url' => ['/server/server/index'],
                    ],
                    'switch' => [
                        'label' => Yii::t('hipanel:server', 'Switches'),
                        'url' => ['/server/hub/index'],
                        'visible' => $user->can('hub.read'),
                    ],
                ],
            ],
        ];
    }
}
