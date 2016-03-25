<?php

namespace hipanel\modules\server;

class Plugin extends \hiqdev\pluginmanager\Plugin
{
    protected $_items = [
        'aliases' => [
            "@server" => "/server/server",
            "@rrd" => "/server/rrd",
            "@switch-graph" => "/server/switch-graph",
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
                    'hipanel/server*' => [
                        'class' => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@hipanel/modules/server/messages',
                        'fileMap' => [
                            'hipanel/server' => 'server.php',
                            'hipanel/server/os' => 'os.php',
                            'hipanel/server/panel' => 'panel.php',
                            'hipanel/server/rrd' => 'rrd.php',
                        ],
                    ],
                    'omnilight/daterangepicker' => [ // TODO: get rid after PluginManager removing
                        'class' => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@omnilight/daterangepicker/messages',
                        'sourceLanguage' => 'en-US',
                    ]
                ],
            ],
        ],
    ];

}
