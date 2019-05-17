<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@server' => '/server/server',
        '@rrd' => '/server/rrd',
        '@switch-graph' => '/server/switch-graph',
        '@pre-order' => '/server/pre-order',
        '@hub' => '/server/hub',
    ],
    'modules' => [
        'server' => [
            'class' => \hipanel\modules\server\Module::class,
            'orderIsAllowed' => $params['module.server.orderIsAllowed'] ?? true,
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'omnilight/daterangepicker' => [ // TODO: get rid after PluginManager removing
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@omnilight/daterangepicker/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'hipanel:server' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                ],
                'hipanel:server:os' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                ],
                'hipanel:server:rrd' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                ],
                'hipanel:server:order' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                ],
                'hipanel:server:order:purpose' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                ],
                'hipanel:server:panel' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                ],
                'hipanel:server:hub' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                ],
                'hipanel.server.consumption.type' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/modules/server/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hipanel\modules\dashboard\menus\DashboardMenu::class => [
                'add' => [
                    'server' => [
                        'menu' => [
                            'class' => \hipanel\modules\server\menus\DashboardItem::class,
                        ],
                        'where' => [
                            'after' => ['certificates', 'domains', 'finance', 'clients', 'dashboard'],
                            'before' => ['hosting'],
                        ],
                    ],
                ],
            ],
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'add' => [
                    'server' => [
                        'menu' => \hipanel\modules\server\menus\SidebarMenu::class,
                        'where' => [
                            'after' => ['certificates', 'domains', 'finance', 'clients', 'dashboard'],
                            'before' => ['hosting'],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
