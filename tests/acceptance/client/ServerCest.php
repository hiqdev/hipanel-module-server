<?php

namespace hipanel\modules\server\tests\acceptance\client;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Step\Acceptance\Client;

class ServerCest
{
    public function ensureIndexPageWorks(Client $I)
    {
        $I->login();
        $I->needPage(Url::to('@server'));
        $I->see('Servers', 'h1');
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkServerSearchBox($I);
    }

    private function ensureICanSeeAdvancedSearchBox(Client $I)
    {
        $I->seeLink('Buy server', Url::to('/server/order/index'));
        $I->see('Advanced search', 'h3');

        $index = new IndexPage($I);
        $index->containsFilters('form-advancedsearch-server-search', [
            ['input' => [
                'id' => 'serversearch-name_like',
                'placeholder' => 'Name',
            ]],
            ['input' => [
                'id' => 'serversearch-note_like',
                'placeholder' => 'Note',
            ]],
            ['input' => [
                'id' => 'serversearch-ip_like',
                'placeholder' => 'IP',
            ]],
            ['input' => ['placeholder' => 'Type']],
            ['input' => ['placeholder' => 'Status']],
        ]);
    }

    private function ensureICanSeeBulkServerSearchBox(Client $I)
    {
        $index = new IndexPage($I);
        $index->containsBulkButtons([
            ["//button[@type='button']" => 'Basic actions'],
        ]);
        $index->containsColumns('bulk-server-search', [
            'Name',
            'IPs',
            'Status',
            'Expires',
            'Tariff',
        ]);
    }
}
