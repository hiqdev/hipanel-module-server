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
                'label' => Yii::t('hipanel:server', 'unused: UU'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'setup: SETUP'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'delivery: DLVR'),
                'color' => '#50c878',
                'rule' => $this->model->type === 'delivery',
            ],
            [
                'label' => Yii::t('hipanel:server', 'reserved: RSVD'),
                'color' => '#339966',
                'rule' => $this->model->type === 'reserved',
            ],
            [
                'label' => Yii::t('hipanel:server', 'dedicated: DSS'),
                'color' => '#AAFFAA',
                'rule' => $this->model->type === 'dedicated',
            ],
            [
                'label' => Yii::t('hipanel:server', 'unmanaged: DSU'),
                'color' => '#AAFFAA',
                'rule' => $this->model->type === 'unmanaged',
            ],
            [
                'label' => Yii::t('hipanel:server', 'virtual: SH'),
                'color' => '#CCCCFF',
                'rule' => $this->model->type === 'virtual',
            ],
            [
                'label' => Yii::t('hipanel:server', 'system: IU'),
                'color' => '#FFFF99',
                'rule' => $this->model->type === 'system',
            ],
            [
                'label' => Yii::t('hipanel:server', 'remote: RS'),
                'color' => '#CCFFCC',
                'rule' => $this->model->type === 'remote',
            ],
            [
                'label' => Yii::t('hipanel:server', 'vdsmaster: VM'),
                'color' => '#DD7700',
                'rule' => $this->model->type === 'vdsmaster',
            ],
            [
                'label' => Yii::t('hipanel:server', 'vds: VDS'),
                'color' => '#FFBB00',
                'rule' => $this->model->type === 'vds',
            ],
            [
                'label' => Yii::t('hipanel:server', 'avdsnode: aVDSnode'),
                'color' => '#b8860b',
                'rule' => $this->model->type === 'avdsnode',
            ],
            [
                'label' => Yii::t('hipanel:server', 'avds: XEN'),
                'color' => '#eedc82',
                'rule' => $this->model->type === 'avds',
            ],
            [
                'label' => Yii::t('hipanel:server', 'ovds: OpenVZ'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'svds: XENSSD'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdn: vCDN.service'),
                'color' => '#6699FF',
                'rule' => $this->model->type === 'cdn',
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnv2: vCDN.node'),
                'color' => '#6699FF',
                'rule' => $this->model->type === 'cdnv2',
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnpix: pCDN.service'),
                'color' => '#c9a0dc',
                'rule' => $this->model->type === 'cdnpix',
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnstat: pCDN.node'),
                'color' => '#c9a0dc',
                'rule' => $this->model->type === 'cdnstat',
            ],
            [
                'label' => Yii::t('hipanel:server', 'cloudstorage: CLDStor.node '),
                'color' => '#aaccee',
                'rule' => $this->model->type === 'cloudstorage',
            ],
            [
                'label' => Yii::t('hipanel:server', 'jail: JL'),
                'color' => '#AAFFFF',
                'rule' => $this->model->type === 'jail',
            ],
            [
                'label' => Yii::t('hipanel:server', 'nic: NC'),
                'color' => '#FFFFDD',
                'rule' => $this->model->type === 'nic',
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink1: U1'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink1',
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink2: U2'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink2',
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink3: U3'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink3',
            ],
            [
                'label' => Yii::t('hipanel:server', 'total: TOTAL'),
                'color' => '#a3a375',
                'rule' => $this->model->type === 'total',
            ],
            [
                'label' => Yii::t('hipanel:server', 'transit: TS'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'transit',
            ],
            [
                'label' => Yii::t('hipanel:server', 'stock: STOCK'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'deleted: DEL'),
                'color' => '#CCCCCC',
                'rule' => $this->model->type === 'deleted',
            ],
            [
                'label' => Yii::t('hipanel:server', 'office: OFFICE'),
            ],
        ];
    }
}
