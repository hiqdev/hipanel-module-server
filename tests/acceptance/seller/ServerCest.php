<?php

namespace hipanel\modules\server\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Seller;

class ServerCest
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
        $I->login();
        $I->needPage(Url::to('@server'));
        $I->see('Servers', 'h1');
        $I->seeLink('Buy server', Url::to('/server/order/index'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkServerSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Name'),
            Input::asAdvancedSearch($I, 'Internal note'),
            Input::asAdvancedSearch($I, 'Order'),
            Input::asAdvancedSearch($I, 'DC'),
            Input::asAdvancedSearch($I, 'IP'),
            Select2::asAdvancedSearch($I, 'Client'),
            Select2::asAdvancedSearch($I, 'Reseller'),
            Input::asAdvancedSearch($I, 'HW summary'),
            Select2::asAdvancedSearch($I, 'Type'),
            Select2::asAdvancedSearch($I, 'Status'),
            Input::asAdvancedSearch($I, 'Switch'),
            Input::asAdvancedSearch($I, 'KVM'),
            Input::asAdvancedSearch($I, 'APC'),
            Input::asAdvancedSearch($I, 'Rack'),
            Input::asAdvancedSearch($I, 'MAC'),
            Input::asAdvancedSearch($I, 'Tariff'),
        ]);
    }

    private function ensureICanSeeLegendBox()
    {
        $this->index->containsLegend([
            'unused: UU',
            'setup: SETUP',
            'delivery: DLVR',
            'reserved: RSVD',
            'dedicated: DSS',
            'unmanaged: DSU',
            'virtual: SH',
            'system: IU',
            'remote: RS',
            'vdsmaster: VM',
            'vds: VDS',
            'avdsnode: aVDSnode',
            'avds: XEN',
            'ovds: OpenVZ',
            'svds: XENSSD',
            'cdn: vCDN.service',
            'cdnv2: vCDN.node',
            'cdnpix: pCDN.service',
            'cdnstat: pCDN.node',
            'cloudstorage: CLDStor.node',
            'jail: JL',
            'nic: NC',
            'uplink1: U1',
            'uplink2: U2',
            'uplink3: U3',
            'total: TOTAL',
            'transit: TS',
            'stock: STOCK',
            'deleted: DEL',
            'office: OFFICE',
        ]);
    }

    private function ensureICanSeeBulkServerSearchBox()
    {
        $this->index->containsBulkButtons([
            'Sell',
            'Basic actions',
        ]);
        $this->index->containsColumns([
            'Name',
            'Client',
            'Reseller',
            'IPs',
            'Status',
            'Expires',
            'Tariff',
        ], 'common');
        $this->index->containsColumns([
            'IPs',
            'Client',
            'DC',
            'Name',
            'Order',
        ], 'short');
        $this->index->containsColumns([
            'Rack',
            'Client',
            'DC',
            'Name',
            'HW summary',
        ], 'hardware');
        $this->index->containsColumns([
            'Client',
            'Rack',
            'Name',
            'Tariff',
            'HW summary',
        ], 'manager');
        $this->index->containsColumns([
            'DC',
            'Name',
            'Type',
            'Switch',
            'KVM',
            'IPMI',
            'APC',
            'IP',
            'MAC',
        ], 'admin');
    }
}
