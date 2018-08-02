<?php

namespace hipanel\modules\server\tests\acceptance\admin;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Admin;

class ServerCest
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
        $I->needPage(Url::to('@server'));
        $I->see('Servers', 'h1');
        $I->seeLink('Create server', Url::to('@server/create'));
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkServerSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Input('Name'),
            new Input('Internal note'),
            new Input('DC'),
            new Input('IP'),
            new Select2('Client'),
            new Select2('Reseller'),
            new Input('HW summary'),
            new Input('Type'),
            new Input('Status'),
            new Input('Switch'),
            new Input('KVM'),
            new Input('APC'),
            new Input('Rack'),
            new Input('MAC'),
            new Input('Tariff'),
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
