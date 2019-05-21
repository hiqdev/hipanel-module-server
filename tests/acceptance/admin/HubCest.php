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
use hipanel\modules\server\tests\_support\Page\Hub\Update;
use hipanel\modules\server\tests\_support\Page\Hub\View;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Admin;

class HubCest
{
    /** @var IndexPage */
    private $index;

    /** @var Example */
    private $hubData;

    public function _before(Admin $I)
    {
        $this->index = new IndexPage($I);
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
     * @dataProvider createProvider
     * @param Admin $I
     * @param Example $data
     * @throws \Exception
     */
    public function ensureICanCreateHub(Admin $I, Example $data): void
    {
        $createPage = new Create($I);
        $this->hubData = $data;
        $I->needPage(Url::to(['@hub/create']));
        $createPage->fillForm($data)
            ->submitForm()
            ->hasNotErrors();
        $I->closeNotification('Switch was created');
    }

    /**
     * @dataProvider assignHubsProvider
     * @param Admin $I
     * @param Example $data
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanAssignHubs(Admin $I, Example $data): void
    {
        $assignPage = new AssignHubs($I);

        $I->needPage(Url::to(['@hub/index']));

        $this->index->filterBy(
            Input::asTableFilter($I, 'Name'), $this->hubData['name']
        );
        $this->index->openRowMenuByColumnValue('Name', $this->hubData['name']);
        $this->index->chooseRowMenuOption('Switches');

        $assignPage->fillForm($data)
            ->submitForm()
            ->hasNotErrors();
        $I->closeNotification('Switches have been edited');
    }


    /**
     * @param Admin $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanUpdateHub(Admin $I): void
    {
        $I->needPage(Url::to(['@hub/index']));
        $updatePage = new Update($I);
        $this->index->filterBy(
            Input::asTableFilter($I, 'Name'), $this->hubData['name']
        );
        $this->index->openRowMenuByColumnValue('Name', $this->hubData['name']);
        $this->index->chooseRowMenuOption('Update');
        $this->updateHubData();

        $updatePage->fillForm($this->hubData)
            ->submitForm()
            ->hasNotErrors();
        $I->closeNotification('Switch was updated');
    }

    /**
     * @dataProvider createProvider
     * @param Admin $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanDeleteHub(Admin $I): void
    {
        $viewPage = new View($I);

        $I->needPage(Url::to(['@hub/index']));
        $this->index->filterBy(
            Input::asTableFilter($I, 'Name'), $this->hubData['name']
        );
        $this->index->openRowMenuByColumnValue('Name', $this->hubData['name']);
        $this->index->chooseRowMenuOption('View');
        $viewPage->clickAction('Delete');
        $I->acceptPopup();
        $I->closeNotification('Switches have been deleted');
    }

    /**
     * @return array
     */
    protected function createProvider(): array
    {
        return [
            [
                'name' => 'test_switch' . uniqid(),
                'type_id' => 'Switch',
                'inn' => 'test_inn',
                'model' => 'test_model',
                'note' => 'test_note',
            ],
        ];
    }

    protected function updateHubData(): void
    {
        foreach ($this->hubData as $field => $value) {
            if (!in_array($field, ['type_id', 'ip', 'mac', 'name'])) {
                $this->hubData[$field] = $value . '_updated';
            }
        }
    }

    /**
     * @return array
     */
    protected function assignHubsProvider(): array
    {
        return [
            [
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
