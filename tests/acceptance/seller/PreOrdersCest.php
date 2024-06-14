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
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Seller;

class PreOrdersCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Seller $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Seller $I)
    {
        $I->markTestSkipped("Incomplete test");
        $I->login();
        $I->needPage(Url::to('@pre-order'));
        $I->see('Pending confirmation servers', 'h1');
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
            'User comment',
            'Tech comment',
            'Time',
        ]);
    }
}
