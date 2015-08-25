<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server;

class SidebarMenu extends \hipanel\base\Menu implements \yii\base\BootstrapInterface
{

    protected $_addTo = 'sidebar';

    protected $_where = [
        'after'     => ['domains', 'tickets', 'finance', 'clients', 'dashboard'],
        'before'    => ['hosting'],
    ];

    protected $_items = [
        'servers' => [
            'label' => 'Servers',
            'url'   => ['/server/server/index'],
            'icon'  => 'fa-server',
            'items' => [
                'servers' => [
                    'label' => 'Servers',
                    'url'   => ['/server/server/index'],
                ],
            ],
        ],
    ];

}
