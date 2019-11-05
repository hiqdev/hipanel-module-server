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

use Codeception\Example;
use hipanel\helpers\Url;
use hipanel\modules\server\tests\_support\Page\Hub\AssignHubs;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Admin;

class ServerAssingHubsCest
{
    /**
     * @var string|null
     */
    protected $serverId;

    /**
     * @var IndexPage
     */
    protected $indexPage;

    /**
     * @inheritDoc
     */
    public function _before(Admin $I)
    {
        $this->indexPage = new IndexPage($I);
    }

    /**
     * @dataProvider assignHubsProvider
     *
     * @param Admin $I
     * @param Example $data
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanAssignHubs(Admin $I, Example $data)
    {
        $assignPage = new AssignHubs($I);
        $this->serverId = $this->getTestServerId($I);

        $I->needPage(Url::to('@server/assign-hubs?id=' . $this->serverId));

        $assignPage->fillForm($data)
            ->hasNotErrors()
            ->submitForm();

        $I->see($data['net_id']);
    }

    /**
     * @dataProvider assignEmptyHubsProvider
     *
     * @param Admin $I
     * @param Example $data
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanRemoveAssignedHubs(Admin $I, Example $data)
    {
        $assignPage = new AssignHubs($I);
        $this->serverId = $this->getTestServerId($I);

        $I->needPage(Url::to('@server/assign-hubs?id=' . $this->serverId));

        $assignPage->fillForm($data)
            ->hasNotErrors()
            ->submitForm();

        $I->cantSeeInField('h3[class*="box-title"]', 'Switches');
    }

    /**
     * @param Admin $I
     * @return string|null
     * @throws \Codeception\Exception\ModuleException
     */
    private function getTestServerId(Admin $I): ?string
    {
        $I->needPage(Url::to('@server'));
        $this->indexPage->filterBy((new Select2($I, "tr.filters select[name*=client]")), 'hipanel_test_reseller');
        $this->indexPage->openRowMenuByColumnValue('DC', 'TEST01');
        $this->indexPage->chooseRowMenuOption('View');
        return $I->grabFromCurrentUrl('~id=(\d+)~');
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
                'net2_id'       => 'TEST-SW-05',
                'net2_port'     => 'port' . uniqid(),
                'pdu2_id'       => 'TEST-SW-06',
                'pdu2_port'     => 'port' . uniqid(),
                'kvm_id'        => 'TEST-SW-04',
                'kvm_port'      => 'port' . uniqid(),
                'pdu_id'        => 'TEST-SW-06',
                'pdu_port'      => 'port' . uniqid(),
                'rack_id'       => 'TEST-SW-02',
                'rack_port'     => 'port' . uniqid(),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function assignEmptyHubsProvider(): array
    {
        return [
            [
                'net_id'        => '',
                'net2_id'       => '',
                'pdu2_id'       => '',
                'kvm_id'        => '',
                'pdu_id'        => '',
                'rack_id'       => '',
            ],
        ];
    }
}
