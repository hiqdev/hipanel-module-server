<?php declare(strict_types=1);
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\tests\acceptance\seller;

use hipanel\modules\server\tests\_support\Helper\OrderServerHelper;
use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Manager;
use hipanel\tests\_support\Step\Acceptance\Seller;

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

    /**
     * @skip
     */
    public function ensureIndexPageWorks(Seller $I)
    {
        $I->login();
        $menu = new SidebarMenu($I);
        $menu->ensureContains('Servers', [
            'Order server' => '/server/order/index',
        ]);
    }
}
