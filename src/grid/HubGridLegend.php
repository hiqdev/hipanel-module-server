<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\widgets\gridLegend\BaseGridLegend;
use hipanel\widgets\gridLegend\GridLegendInterface;
use Yii;

class HubGridLegend extends BaseGridLegend implements GridLegendInterface
{
    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:server:hub', 'Switch'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'KVM'),
                'color' => '#fcf8e3',
                'rule' => $this->model->type === 'kvm',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'APC'),
                'color' => '#dff0d8',
                'rule' => $this->model->type === 'pdu',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'IPMI'),
                'color' => '#d9edf7',
                'rule' => $this->model->type === 'ipmi',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'Module'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'Rack'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'Camera'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'Cable organizer'),
                'columns' => ['actions'],
            ],
        ];
    }
}
