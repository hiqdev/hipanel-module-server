<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\tests\acceptance\admin;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Admin;

class HubIndexCest
{
    /** @var IndexPage */
    private $indexPage;

    public function _before(Admin $I)
    {
        $this->indexPage = new IndexPage($I);
    }

    /**
     * @param Admin $I
     */
    public function ensureIndexPageWorks(Admin $I): void
    {
        $I->needPage(Url::to('@hub'));
        $I->see('Switches', 'h1');
        $I->seeLink('Create switch', Url::to('@hub/create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkServerSearchBox();
    }

    /**
     * @param Admin $I
     */
    private function ensureICanSeeAdvancedSearchBox(Admin $I): void
    {
        $this->indexPage->containsFilters([
            Input::asAdvancedSearch($I, 'Switch'),
            Input::asAdvancedSearch($I, 'INN'),
            Input::asAdvancedSearch($I, 'IP'),
            Input::asAdvancedSearch($I, 'MAC address'),
            Input::asAdvancedSearch($I, 'Model'),
            Input::asAdvancedSearch($I, 'Order No.'),
            (Dropdown::asAdvancedSearch($I, 'Type'))->withItems([
                'Switch',
                'KVM',
                'APC',
                'Rack',
                'IPMI',
                'Module',
            ]),
            Select2::asAdvancedSearch($I, 'Buyer'),
            Input::asAdvancedSearch($I, 'Tariff'),
            Input::asAdvancedSearch($I, 'Rack'),
        ]);
    }

    private function ensureICanSeeLegendBox(): void
    {
        $this->indexPage->containsLegend([
            'Switch',
            'KVM',
            'APC',
            'IPMI',
            'Module',
            'Rack',
            'Camera',
            'Cable organizer',
        ]);
    }

    private function ensureICanSeeBulkServerSearchBox(): void
    {
        $this->indexPage->containsBulkButtons([
            'Update',
        ]);
        $this->indexPage->containsColumns([
            'Name',
            'INN',
            'Model',
            'Type',
            'IP',
            'MAC address',
        ]);
    }
}

