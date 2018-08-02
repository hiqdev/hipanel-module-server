<?php

namespace hipanel\modules\server\tests\acceptance\admin;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Admin;

class SwitchesCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Admin $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Admin $I)
    {
        $I->login();
        $I->needPage(Url::to('@hub'));
        $I->see('Switches', 'h1');
        $I->seeLink('Create switch', Url::to('@hub/create'));
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkServerSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Input('Switch'),
            new Input('INN'),
            new Input('IP'),
            new Input('MAC address'),
            new Input('Model'),
            new Input('Order No.'),
            (new Dropdown('hubsearch-type_id'))->withItems([
                'Switch',
                'KVM',
                'APC',
                'Rack',
                'IPMI',
                'Module',
            ]),
        ]);
    }

    private function ensureICanSeeLegendBox()
    {
        $this->index->containsLegend([
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

    private function ensureICanSeeBulkServerSearchBox()
    {
        $this->index->containsBulkButtons([
            'Update',
        ]);
        $this->index->containsColumns([
            'Name',
            'INN',
            'Model',
            'Type',
            'IP',
            'MAC address',
        ]);
    }
}
