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

use hipanel\helpers\Url;
use hipanel\tests\_support\Step\Acceptance\Seller;

class OrderServerCest
{
    /**
     * @skip
     */
    public function ensureIndexPageWorks(Seller $I)
    {
        $I->login();
        $I->needPage(Url::to('/server/order/index'));
        $I->see('Order server', 'h1');
        $I->see('OpenVZ', 'h3');
        $I->see('XEN SSD', 'h3');
        $I->seeLink('ORDER SERVER', Url::to('/server/order/open-vz'));
        $I->seeLink('ORDER SERVER', Url::to('/server/order/xen-ssd'));
        $I->see(<<<MSG
VDS based on OpenVZ - is an inexpensive and reliable solution for small projects 
that do not require many resources 
(HTML web-sites, landing pages, small blogs, personal websites, business cards, etc.). 
An additional advantage of our VDS based on OpenVZ is utilization 
of SSD cache system that improves performance of the disk subsystem 
during frequently accessed data readings.
MSG
, 'p');
        $I->see(<<<MSG
The main advantage of a VDS based on XEN with SSD is speed. 
It is more than 250 times faster than a conventional HDD. 
Due to Xen virtualization type, all resources are assigned to user 
and the operation of your VDS does not depend on the main server's load. 
Virtual dedicated server based on Xen is a perfect solution for most medium and large projects 
because of its performance that is highly competitive with the performance of a dedicated server.
MSG
, 'p');
    }
}
