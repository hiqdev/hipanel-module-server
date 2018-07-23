<?php

namespace hipanel\modules\server\widgets\combo;

use hiqdev\combo\Combo;
use yii\helpers\ArrayHelper;

class HubCombo extends Combo
{
    const IPMI = 'net';
    const KVM = 'kvm';
    const NET = 'net';
    const PDU = 'pdu';
    const RACK = 'rack';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $type = 'server/hub';

    /** {@inheritdoc} */
    public $url = '/server/hub/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /**
     * {@inheritdoc}
     */
    public $primaryFilter = 'name_ilike';

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    public $hubType;

    /** {@inheritdoc} */
    public function getFilter()
    {
        if ($this->hubType) {
            return ArrayHelper::merge(parent::getFilter(), [
                'type' => ['format' => $this->getHubType()],
                'limit' => ['format' => '50'],
            ]);
        }

        return parent::getFilter();
    }

    private function getHubType()
    {
        return $this->hubType;
    }
}

