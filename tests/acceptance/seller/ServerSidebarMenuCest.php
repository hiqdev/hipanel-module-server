<?php

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
            'Order server' => '/server/order/index',
            'Pre-orders' => '@pre-order/index',
            'Refuses' => '/server/refuse/index',
        ]);

        $menu->ensureDoesNotContain('Servers',[
            'Switches',
        ]);
    }
}
