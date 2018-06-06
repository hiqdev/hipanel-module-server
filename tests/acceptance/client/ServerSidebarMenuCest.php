<?php

namespace hipanel\modules\server\tests\acceptance\client;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Client;

class ServerSidebarMenuCest
{
    public function ensureMenuIsOk(Client $I)
    {
        $menu = new SidebarMenu($I);

        $menu->ensureContains('Servers', [
            'Servers' => '@server/index',
            'Order server' => '/server/order/index',
        ]);

        $menu->ensureDoesNotContain('Servers',[
            'Pre-orders',
            'Refuses',
            'Switches',
        ]);
    }
}
