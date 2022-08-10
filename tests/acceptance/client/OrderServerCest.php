<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\tests\acceptance\client;

use hipanel\modules\server\tests\_support\Helper\OrderServerHelper;
use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Client;
use hipanel\tests\_support\Step\Acceptance\Manager;

class OrderServerCest
{
    private OrderServerHelper $orderServerHelper;

    protected function _inject(OrderServerHelper $orderServerHelper): void
    {
        $this->orderServerHelper = $orderServerHelper;
    }

    public function _before(Manager $I, $scenario): void
    {
        if (!$this->orderServerHelper->canSeeOrderServer()) {
            $scenario->skip($this->orderServerHelper->getDisabledMessage());
        }
    }

    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $menu = new SidebarMenu($I);
        $menu->ensureContains('Servers', [
            'Order server' => '/server/order/index',
        ]);
    }
}
