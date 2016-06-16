<?php

/*
* Server module for HiPanel
*
* @link      https://github.com/hiqdev/hipanel-module-server
* @package   hipanel-module-server
* @license   BSD-3-Clause
* @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
*/

return [
    'aliases' => [
        '@server' => '/server/server',
        '@rrd' => '/server/rrd',
        '@switch-graph' => '/server/switch-graph',
    ],
    'modules' => [
        'server' => [
            'class' => \hipanel\modules\server\Module::class,
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'hipanel/server*' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                    'forceTranslation' => true,
                    'fileMap' => [
                        'hipanel/server' => 'server.php',
                        'hipanel/server/os' => 'os.php', // outer
                        'hipanel/server/rrd' => 'rrd.php',
                        'hipanel/server/order' => 'order.php',
                        'hipanel/server/order/purpose' => 'purpose.php', // outer
                    ],
                ],
                'hipanel/server/panel' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                    'forceTranslation' => true,
                    'fileMap' => [
                        'hipanel/server/panel' => 'panel.php',
                    ],
                ],
                'omnilight/daterangepicker' => [ // TODO: get rid after PluginManager removing
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@omnilight/daterangepicker/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'menuManager' => [
            'menus' => [
                'server' => \hipanel\modules\server\SidebarMenu::class,
            ],
        ],
    ],
];
