<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\tests\acceptance\seller;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Seller;

class ServerSidebarMenuCest
{
    public function ensureMenuIsOk(Seller $I)
    {
        $menu = new SidebarMenu($I);

        $menu->ensureContains('Servers', [
            'Servers' => '@server/index',
//            'Order server' => '/server/order/index',
//            'Pre-orders' => '@pre-order/index',
//            'Refuses' => '/server/refuse/index',
        ]);

        $menu->ensureDoesNotContain('Servers',[
            'Switches',
        ]);
    }
}
