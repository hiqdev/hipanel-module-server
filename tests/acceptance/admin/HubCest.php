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
use hipanel\modules\server\tests\_support\Page\Hub\Options;
use hipanel\modules\server\tests\_support\Page\Hub\Update;
use hipanel\modules\server\tests\_support\Page\Hub\View;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Admin;

class HubCest
{
    /** @var IndexPage */
    private $indexPage;

    /** @var View */
    private $viewPage;

    /** @var Example */
    private $hubData;

    public function _before(Admin $I)
    {
        $this->indexPage = new IndexPage($I);
        $this->viewPage = new View($I);
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

        $I->needPage(Url::to(['@hub/create']));
        $createPage->fillForm($data)
            ->hasNotErrors()
            ->submitForm();

        $I->closeNotification('Switch was created');
        $I->seeInCurrentUrl(Url::to(['@hub/view']));
        $this->viewPage->check($data);
        $this->hubData = $data;
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

        $this->indexPage->filterBy(
            Input::asTableFilter($I, 'Name'), $this->hubData['name']
        );
        $this->indexPage->openRowMenuByColumnValue('Name', $this->hubData['name']);
        $this->indexPage->chooseRowMenuOption('Switches');

        $assignPage->fillForm($data)
            ->hasNotErrors()
            ->submitForm();
        $I->closeNotification('Switches have been edited');
        $I->seeInCurrentUrl(Url::to(['@hub/view']));
        $this->viewPage->check($data);
    }

    /**
     * @dataProvider optionsDataProvider
     * @param Admin $I
     * @param Example $data
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanSetHubOptions(Admin $I, Example $data): void
    {
        $optionsPage = new Options($I);

        $I->needPage(Url::to(['@hub/index']));

        $this->indexPage->filterBy(
            Input::asTableFilter($I, 'Name'), $this->hubData['name']
        );
        $this->indexPage->openRowMenuByColumnValue('Name', $this->hubData['name']);
        $this->indexPage->chooseRowMenuOption('Options');

        $optionsPage->fillForm($data)
            ->hasNotErrors()
            ->submitForm();
        $I->closeNotification('Options was updated');
        $I->seeInCurrentUrl(Url::to(['@hub/view']));
        $this->viewPage->check($data);
    }

    /**
     * @param Admin $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanUpdateHub(Admin $I): void
    {
        $updatePage = new Update($I);

        $I->needPage(Url::to(['@hub/index']));
        $this->indexPage->filterBy(
            Input::asTableFilter($I, 'Name'), $this->hubData['name']
        );
        $this->indexPage->openRowMenuByColumnValue('Name', $this->hubData['name']);
        $this->indexPage->chooseRowMenuOption('Update');
        $this->updateHubData();

        $updatePage->fillForm($this->hubData)
            ->hasNotErrors()
            ->submitForm();
        $I->closeNotification('Switch was updated');
        $I->seeInCurrentUrl(Url::to(['@hub/view']));
        $data = $this->hubData;
        unset($data['note']); // cause the xEditable plugin cut the `note` link
        $this->viewPage->check($this->hubData);
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
        $this->indexPage->filterBy(
            Input::asTableFilter($I, 'Name'), $this->hubData['name']
        );
        $this->indexPage->openRowMenuByColumnValue('Name', $this->hubData['name']);
        $this->indexPage->chooseRowMenuOption('View');
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
                'name'    => 'test_switch' . uniqid(),
                'type_id' => 'Switch',
                'inn'     => 'test_inn',
                'model'   => 'test_model',
                'note'    => 'test_note',
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
                'net_id'        => 'TEST-SW-05',
                'net_port'      => 'port' . uniqid(),
                'kvm_id'        => 'TEST-SW-04',
                'kvm_port'      => 'port' . uniqid(),
                'pdu_id'        => 'TEST-SW-06',
                'pdu_port'      => 'port' . uniqid(),
                'rack_id'       => 'TEST-SW-02',
                'rack_port'     => 'port' . uniqid(),
                'location_id'   => 'TEST-SW-03',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function optionsDataProvider(): array
    {
        return [
            [
                'inn'               => 'test_inn_option',
                'model'             => 'test_model_option',
                'ports_num'         => 42,
                'community'         => 'hiqdev',
                'nic_media'         => '100 Gbit/s',
                'digit_capacity_id' => 'vds2',
                'base_port_no'      => 21,
            ],
        ];
    }
}
