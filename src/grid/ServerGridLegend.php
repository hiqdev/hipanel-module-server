<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

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
                'label' => Yii::t('hipanel:server', 'deleted: DEL'),
                'color' => '#CCCCCC',
                'rule' => $this->model->state === 'deleted',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'unused: UU'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'setup: SETUP'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'delivery: DLVR'),
                'color' => '#50c878',
                'rule' => $this->model->type === 'delivery',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'reserved: RSVD'),
                'color' => '#339966',
                'rule' => $this->model->type === 'reserved',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'dedicated: DSS'),
                'color' => '#AAFFAA',
                'rule' => $this->model->type === 'dedicated',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'unmanaged: DSU'),
                'color' => '#AAFFAA',
                'rule' => $this->model->type === 'unmanaged',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'virtual: SH'),
                'color' => '#CCCCFF',
                'rule' => $this->model->type === 'virtual',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'system: IU'),
                'color' => '#FFFF99',
                'rule' => $this->model->type === 'system',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'remote: RS'),
                'color' => '#CCFFCC',
                'rule' => $this->model->type === 'remote',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'vdsmaster: VM'),
                'color' => '#DD7700',
                'rule' => $this->model->type === 'vdsmaster',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'vds: VDS'),
                'color' => '#FFBB00',
                'rule' => $this->model->type === 'vds',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'avdsnode: aVDSnode'),
                'color' => '#b8860b',
                'rule' => $this->model->type === 'avdsnode',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'avds: XEN'),
                'color' => '#eedc82',
                'rule' => $this->model->type === 'avds',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'ovds: OpenVZ'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'svds: XENSSD'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdn: vCDN.service'),
                'color' => '#6699FF',
                'rule' => $this->model->type === 'cdn',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnv2: vCDN.node'),
                'color' => '#6699FF',
                'rule' => $this->model->type === 'cdnv2',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnpix: pCDN.service'),
                'color' => '#c9a0dc',
                'rule' => $this->model->type === 'cdnpix',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'cdnstat: pCDN.node'),
                'color' => '#c9a0dc',
                'rule' => $this->model->type === 'cdnstat',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'cloudstorage: CLDStor.node '),
                'color' => '#aaccee',
                'rule' => $this->model->type === 'cloudstorage',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'jail: JL'),
                'color' => '#AAFFFF',
                'rule' => $this->model->type === 'jail',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'nic: NC'),
                'color' => '#FFFFDD',
                'rule' => $this->model->type === 'nic',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink1: U1'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink1',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink2: U2'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink2',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'uplink3: U3'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'uplink3',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'total: TOTAL'),
                'color' => '#a3a375',
                'rule' => $this->model->type === 'total',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'transit: TS'),
                'color' => '#EBEBCD',
                'rule' => $this->model->type === 'transit',
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'stock: STOCK'),
                'columns' => ['actions'],
            ],
            [
                'label' => Yii::t('hipanel:server', 'office: OFFICE'),
                'columns' => ['actions'],
            ],
        ];
    }
}
