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

use Codeception\Example;
use hipanel\helpers\Url;
use hipanel\modules\server\tests\_support\Page\Hub\AssignHubs;
use hipanel\modules\server\tests\_support\Page\Hub\Create;
use hipanel\modules\server\tests\_support\Page\Hub\Delete;
use hipanel\modules\server\tests\_support\Page\Hub\Update;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Admin;

class HubCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Admin $I)
    {
        $this->index = new IndexPage($I);
    }

    /**
     * @param Admin $I
     */
    public function ensureIndexPageWorks(Admin $I): void
    {
        $I->login();
        $I->needPage(Url::to('@hub'));
        $I->see('Switches', 'h1');
        $I->seeLink('Create switch', Url::to('@hub/create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkServerSearchBox();
    }

    /**
     * @dataProvider createProvider
     * @param Admin $I
     * @param Example $data
     */
    public function ensureICanCreateHub(Admin $I, Example $data): void
    {
        $I->needPage(Url::to(['@hub/create']));
        $page = new Create($I);
        $page->fillForm($data);
        $page->submitForm();
        $I->seeInCurrentUrl('hub/view?id');
        $page->check($data);
    }

    /**
     * @dataProvider assignHubsProvider
     * @param Admin $I
     * @param Example $data
     */
    public function ensureICanAssignHubs(Admin $I, Example $data): void
    {
        $I->login();
        $I->needPage(Url::to(['@hub/index']));
        $page = new AssignHubs($I);
        $page->needPageByName($data['hub_name']);
        $page->needGoToAssignForm();
        unset($data['hub_name']);
        $page->fillAssignForm($data);
        $page->submitForm();
        $page->checkAssigned($data);
    }


    /**
     * @dataProvider updateProvider
     * @param Admin $I
     * @param Example $data
     */
    public function ensureICanUpdateHub(Admin $I, Example $data): void
    {
        $I->login();
        $I->needPage(Url::to(['@hub/index']));
        $page = new Update($I);
        $name = str_replace('_updated', '', $data['name']);
        $page->needPageByName($name);
        $page->needUpdatePage();
        $page->fillForm($data);
        $page->submitForm();
        $I->seeInCurrentUrl('hub/view?id');
        $page->check($data);
    }

    /**
     * @dataProvider updateProvider
     * @param Admin $I
     * @param Example $data
     */
    public function ensureICanDeleteHub(Admin $I, Example $data): void
    {
        $I->login();
        $I->needPage(Url::to(['@hub/index']));
        $page = new Delete($I);
        $page->needPageByName($data['name']);
        $page->deleteHub($data['name']);
    }

    /**
     * @return array
     */
    protected function createProvider(): array
    {
        return [
            [
                'name' => 'test_switch',
                'type_id' => 'Switch',
                'mac' => '00:27:0e:2a:b9:aa',
                'inn' => 'test_inn',
                'ip' => '127.0.0.1',
                'model' => 'test_model',
                'note' => 'test_note',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function updateProvider(): array
    {
        $result = [];
        foreach ($this->createProvider() as $rows) {
            $data = [];
            foreach ($rows as $field => $value) {
                if (!in_array($field, ['type_id', 'ip', 'mac'])) {
                    $data[$field] = $value . '_updated';
                }
            }
            $result[] = $data;
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function optionsProvider(): array
    {
        return [
            [
            ],
        ];
    }

    /**
     * @return array
     */
    protected function assignHubsProvider(): array
    {
        return [
            [
                'hub_name' => 'test_switch',
                'net_id' => 'TEST-SW-05',
                'net_port' => 'port5',
                'kvm_id' => 'TEST-SW-04',
                'kvm_port' => 'port4',
                'pdu_id' => 'TEST-SW-06',
                'pdu_port' => 'port6',
                'rack_id' => 'TEST-SW-02',
                'rack_port' => 'port2',
                'console_id' => null,
                'console_port' => null,
                'location_id' => 'TEST-SW-03',
                'location_port' => '',
            ],
        ];
    }

    private function ensureICanSeeAdvancedSearchBox(Admin $I)
    {
        $this->index->containsFilters([
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
