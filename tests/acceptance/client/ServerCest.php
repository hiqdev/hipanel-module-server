<?php

namespace hipanel\modules\server\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Client;

class ServerCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Client $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('@server'));
        $I->see('Servers', 'h1');
        $I->seeLink('Buy server', Url::to('/server/order/index'));
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeBulkServerSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Input('Name'),
            new Input('Note'),
            new Input('IP'),
            new Input('Type'),
            new Input('Status'),
        ]);
    }

    private function ensureICanSeeBulkServerSearchBox()
    {
        $this->index->containsBulkButtons([
            'Basic actions',
        ]);
        $this->index->containsColumns([
            'Name',
            'IPs',
            'Status',
            'Expires',
            'Tariff',
        ]);
    }
}
