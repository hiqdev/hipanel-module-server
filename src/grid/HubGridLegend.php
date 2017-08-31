<?php

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
                'color' => '#FFFF99',
                'rule' => $this->model->type === 'kvm',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'APC'),
                'color' => '#AAFFAA',
                'rule' => $this->model->type === 'pdu',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'IPMI'),
                'color' => '#AAFFFF',
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
