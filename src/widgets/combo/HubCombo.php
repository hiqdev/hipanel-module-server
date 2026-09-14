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
use yii\web\JsExpression;

class HubCombo extends Combo
{
    const string IPMI = 'net';
    const string KVM = 'kvm';
    const string NET = 'net';
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

    /**
     * Type of the device/row currently being edited (e.g. 'pdu').
     * Used only to decide whether to show the "nic2" daisy-chain preview hint below.
     */
    public $mainObjectType;

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

    /**
     * {@inheritdoc}
     *
     * When the row being edited is itself a PDU, hint that a PDU picked here will end up
     * wired through a management NIC sub-port on the target (`<name>nic2`), not the target
     * device directly. Purely cosmetic — the submitted id is still the real target device id.
     */
    public function getPluginOptions($options = []): array
    {
        $pluginOptions = parent::getPluginOptions($options);

        if ($this->mainObjectType !== self::PDU) {
            return $pluginOptions;
        }

        $hint = new JsExpression("function (data) {
            if (!data.id) {
                return data.text;
            }
            return data.type === 'pdu' ? data.text + 'nic2' : data.text;
        }");

        return ArrayHelper::merge($pluginOptions, [
            'select2Options' => [
                'templateResult' => $hint,
                'templateSelection' => $hint,
            ],
        ]);
    }

    private function getHubTypes(): array
    {
        return $this->hubTypes;
    }
}
