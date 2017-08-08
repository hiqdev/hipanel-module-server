<?php

namespace hipanel\modules\server\grid;

use hipanel\widgets\gridLegend\BaseGridLegend;
use hipanel\widgets\gridLegend\GridLegendInterface;
use Yii;

class ServerGridLegend extends BaseGridLegend implements GridLegendInterface
{
    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:server', 'unused'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'setup'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'delivery'),
                'color' => '#50c878',
                'rule' => $this->model->type === 'delivery',
            ],
            [
                'label' => Yii::t('hipanel:server', 'reserved'),
                'color' => '#339966',
                'rule' => $this->model->type === 'reserved',
            ],
            [
                'label' => Yii::t('hipanel:server', 'dedicated'),
                'color' => '#AAFFAA',
                'rule' => $this->model->type === 'dedicated',
            ],
            [
                'label' => Yii::t('hipanel:server', 'unmanaged'),
                'color' => '#AAFFAA',
                'rule' => $this->model->type === 'unmanaged',
            ],
            [
                'label' => Yii::t('hipanel:server', 'virtual'),
                'color' => '#CCCCFF',
                'rule' => $this->model->type === 'virtual',
            ],
            [
                'label' => Yii::t('hipanel:server', 'system'),
                'color' => '#FFFF99',
                'rule' => $this->model->type === 'system',
            ],
            [
                'label' => Yii::t('hipanel:server', 'remote'),
                'color' => '#CCFFCC',
                'rule' => $this->model->type === 'remote',
            ],
            [
                'label' => Yii::t('hipanel:server', 'vds'),
                'color' => '#FFBB00',
                'rule' => $this->model->type === 'vds',
            ],
            [
                'label' => Yii::t('hipanel:server', 'avdsnode'),
                'color' => '#b8860b',
                'rule' => $this->model->type === 'avdsnode',
            ],
            [
                'label' => Yii::t('hipanel:server', 'avds'),
                'color' => '#eedc82',
                'rule' => $this->model->type === 'avds',
            ],
            [
                'label' => Yii::t('hipanel:server', 'ovds'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'svds'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdn'),
                'color' => '#6699FF',
                'rule' => $this->model->type === 'cdn',
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnv2'),
                'color' => '#6699FF',
                'rule' => $this->model->type === 'cdnv2',
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnpix'),
                'color' => '#c9a0dc',
                'rule' => $this->model->type === 'cdnpix',
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnstat'),
                'color' => '#c9a0dc',
                'rule' => $this->model->type === 'cdnstat',
            ],
            [
                'label' => Yii::t('hipanel:server', 'cloudstorage'),
                'color' => '#aaccee',
                'rule' => $this->model->type === 'cloudstorage',
            ],
            [
                'label' => Yii::t('hipanel:server', 'jail'),
                'color' => '#AAFFFF',
                'rule' => $this->model->type === 'jail',
            ],
            [
                'label' => Yii::t('hipanel:server', 'nic'),
                'color' => '#FFFFDD',
                'rule' => $this->model->type === 'nic',
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink1'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink1',
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink2'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink2',
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink3'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink3',
            ],
            [
                'label' => Yii::t('hipanel:server', 'total'),
                'color' => '#a3a375',
                'rule' => $this->model->type === 'total',
            ],
            [
                'label' => Yii::t('hipanel:server', 'transit'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'transit',
            ],
            [
                'label' => Yii::t('hipanel:server', 'stock'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'deleted'),
                'color' => '#CCCCCC',
                'rule' => $this->model->type === 'deleted',
            ],
            [
                'label' => Yii::t('hipanel:server', 'office'),
            ],
        ];
    }
}
