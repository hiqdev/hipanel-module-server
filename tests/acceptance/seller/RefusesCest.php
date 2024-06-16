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

use hipanel\helpers\Url;
use hipanel\modules\server\tests\_support\Helper\OrderServerHelper;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Manager;
use hipanel\tests\_support\Step\Acceptance\Seller;

class RefusesCest
{
    /**
     * @var IndexPage
     */
    private $index;

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
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Seller $I)
    {
        $I->login();
        $I->needPage(Url::to('/server/refuse'));
        $I->see('Refuses', 'h1');
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkServerSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Select2::asAdvancedSearch($I, 'Client'),
            (Dropdown::asAdvancedSearch($I, 'State'))->withItems([
                'New',
                'Approved',
                'Rejected',
            ]),
        ]);
    }

    private function ensureICanSeeBulkServerSearchBox()
    {
        $this->index->containsBulkButtons([
            'Approve',
            'Reject',
        ]);
        $this->index->containsColumns([
            'Client',
            'Server',
            'User comment',
            'Time',
        ]);
    }
}
