<?php

namespace hipanel\modules\server\tests\acceptance\admin;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Admin;

class ServerSidebarMenuCest
{
    public function ensureMenuIsOk(Admin $I)
    {
        $menu = new SidebarMenu($I);

        $menu->ensureContains('Servers', [
            'Servers' => '@server/index',
            'Switches' => '@hub/index',
        ]);

        $menu->ensureDoesNotContain('Servers', [
            'Order server',
            'Pre-orders',
            'Refuses',
        ]);
    }
}
