<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server;

class Plugin extends \hiqdev\pluginmanager\Plugin
{
    protected $_items = [
        'aliases' => [
            "@server" => "/domain/server",
        ],
        'menus' => [
            [
                'class' => 'hipanel\modules\server\SidebarMenu',
            ],
        ],
        'modules' => [
            'server' => [
                'class' => 'hipanel\modules\server\Module',
            ],
        ],
    ];

}
