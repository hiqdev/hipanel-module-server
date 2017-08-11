<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\menus;

use Yii;

class ServerActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public $model;

    public function items()
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@server/view', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@server/update', 'id' => $this->model->id],
                'visible' => false,
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => Yii::t('hipanel:server', 'Performance graphs'),
                'icon' => 'fa-signal',
                'url' => ['@rrd/view', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => Yii::t('hipanel:server', 'Switch graphs'),
                'icon' => 'fa-area-chart',
                'url' => ['@switch-graph/view', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            'hardware-settings' => [
                'label' => Yii::t('hipanel:server', 'Hardware properties'),
                'icon' => 'fa-cogs',
                'url' => ['@server/hardware-settings', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            'software-settings' => [
                'label' => Yii::t('hipanel:server', 'Software properties'),
                'icon' => 'fa-cogs',
                'url' => ['@server/software-settings', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            'monitoring-settings' => [
                'label' => Yii::t('hipanel:server', 'Monitoring properties'),
                'icon' => 'fa-cogs',
                'url' => ['@server/monitoring-settings', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => Yii::t('hipanel:server', 'Server IPs'),
                'icon' => 'fa-location-arrow',
                'visible' => Yii::getAlias('@ip', false),
                'url' => ['@ip/index', 'IpSearch' => ['server_in' => $this->model->name]],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => Yii::t('hipanel:server', 'Server Accounts'),
                'icon' => 'fa-user',
                'visible' => Yii::getAlias('@account', false),
                'url' => ['@account/index', 'AccountSearch' => ['server' => $this->model->name]],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
        ];
    }
}
