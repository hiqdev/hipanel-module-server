<?php

namespace hipanel\modules\server;

class Plugin extends \hiqdev\pluginmanager\Plugin
{
    protected $_items = [
        'aliases' => [
            "@server" => "/server/server",
        ],
        'menus' => [
            'hipanel\modules\server\SidebarMenu',
        ],
        'modules' => [
            'server' => [
                'class' => 'hipanel\modules\server\Module',
            ],
        ],
    ];

}
