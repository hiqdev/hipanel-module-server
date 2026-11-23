<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets\combo;

use hiqdev\combo\Combo;
use yii\helpers\ArrayHelper;

class HubCombo extends Combo
{
    const string IPMI = 'net';
    const string KVM = 'kvm';
    const string NET = 'net';
    const string NIC_NAME_PATTERN = '/nic\d+$/';
    const string PDU = 'pdu';
    const string RACK = 'rack';
    const string JBOD = 'jbod';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $type = 'server/hub';

    /** {@inheritdoc} */
    public $url = '/server/hub/index';

    /** {@inheritdoc} */
    public $_return = ['id', 'type'];

    /**
     * {@inheritdoc}
     */
    public $primaryFilter = 'name_ilike';

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    public array $hubTypes = [];

    public bool $showDeleted = true;

    /** {@inheritdoc} */
    public function getFilter()
    {
        $filters = parent::getFilter();
        if (!empty($this->hubTypes)) {
            $filters = ArrayHelper::merge($filters, [
                'type_in' => ['format' => $this->getHubTypes()],
                'limit' => ['format' => '50'],
            ]);
        }

        if ($this->showDeleted) {
            $filters = ArrayHelper::merge($filters, [
                'show_deleted' => ['format' => true],
            ]);
        }

        return $filters;
    }

    private function getHubTypes(): array
    {
        return $this->hubTypes;
    }
}
