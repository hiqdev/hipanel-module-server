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
        'components' => [
            'i18n' => [
                'translations' => [
                    'hipanel/server' => [
                        'class' => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@hipanel/modules/server/messages',
                        'fileMap' => [
                            'hipanel/server' => 'server.php',
                            'hipanel/server/os' => 'os.php',
                            'hipanel/server/panel' => 'panel.php',
                        ],
                    ],
                ],
            ],
        ],
    ];

}
