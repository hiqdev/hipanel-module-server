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
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Seller;

class ServerIndexCest
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
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Name'),
            Input::asAdvancedSearch($I, 'Internal note'),
            Input::asAdvancedSearch($I, 'Order'),
            Input::asAdvancedSearch($I, 'IP'),
            Select2::asAdvancedSearch($I, 'Client'),
            Select2::asAdvancedSearch($I, 'Reseller'),
            Input::asAdvancedSearch($I, 'Hardware Summary'),
            Select2::asAdvancedSearch($I, 'Type'),
            Select2::asAdvancedSearch($I, 'Status'),
            Input::asAdvancedSearch($I, 'Tariff'),
            Dropdown::asAdvancedSearch($I, 'Is wizzarded'),
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
}
