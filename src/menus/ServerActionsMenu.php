<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\menus;

use hipanel\modules\server\models\Server;
use hipanel\widgets\AjaxModalWithTemplatedButton;
use hiqdev\yii2\menus\Menu;
use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class ServerActionsMenu extends Menu
{
    /**
     * @var Server
     */
    public $model;

    public function items(): array
    {
        $user = Yii::$app->user;

        $items = [
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
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
                'visible' => Yii::$app->user->can('server.update'),
            ],
            [
                'label' => Yii::t('hipanel:server', 'Renew server'),
                'icon' => 'fa-forward',
                'url' => ['add-to-cart-renewal', 'model_id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
                'visible' => !empty($this->model->expires) && Yii::$app->user->can('server.pay') && Yii::$app->params['module.server.renew.allowed'],
            ],
            'assign-hubs' => [
                'label' => Yii::t('hipanel:server', 'Assign hubs'),
                'icon' => 'fa-plug',
                'url' => ['@server/assign-hubs', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('server.update'),
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => Yii::t('hipanel:server', 'Performance graphs'),
                'icon' => 'fa-signal',
                'url' => ['@rrd/view', 'id' => $this->model->id],
                'visible' => $this->model->canRrd(),
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
            [
                'label' => Yii::t('hipanel:server', 'Server IPs'),
                'icon' => 'fa-location-arrow',
                'url' => ['@ip/index', 'IpSearch' => ['server_in' => $this->model->name]],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
                'visible' => $user->can('ip.read') && Yii::getAlias('@ip', false),
            ],
            [
                'label' => Yii::t('hipanel:server', 'Server Accounts'),
                'icon' => 'fa-user',
                'url' => ['@account/index', 'AccountSearch' => ['server' => $this->model->name]],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
                'visible' => $user->can('account.read') && (bool)Yii::getAlias('@account', false),
            ],
        ];

        array_splice($items, 9, 0, $this->getSettingsItems());

        return $items;
    }

    private function getSettingsItems(): array
    {
        $items = [];

        foreach ([
                     'hardware-settings' => [
                         'label' => Yii::t('hipanel:server', 'Hardware properties'),
                         'url' => ['hardware-settings', 'id' => $this->model->id],
                         'size' => Modal::SIZE_LARGE,
                     ],
                     'software-settings' => [
                         'label' => Yii::t('hipanel:server', 'Software properties'),
                         'url' => ['software-settings', 'id' => $this->model->id],
                     ],
                     'monitoring-settings' => [
                         'label' => Yii::t('hipanel:server', 'Monitoring properties'),
                         'url' => ['monitoring-settings', 'id' => $this->model->id],
                     ],
                     'mail-settings' => [
                         'label' => Yii::t('hipanel:server', 'Mail settings'),
                         'url' => ['mail-settings', 'id' => $this->model->id],
                         'size' => Modal::SIZE_SMALL,
                     ],
                 ] as $key => $item) {
            $items[] = [
                'label' => AjaxModalWithTemplatedButton::widget([
                    'ajaxModalOptions' => [
                        'id' => "{$key}-modal-{$this->model->id}",
                        'bulkPage' => true,
                        'header' => Html::tag('h4', $item['label'], ['class' => 'modal-title']),
                        'scenario' => 'default',
                        'actionUrl' => $item['url'],
                        'size' => $item['size'] ?? Modal::SIZE_DEFAULT,
                        'handleSubmit' => $item['url'],
                        'toggleButton' => [
                            'tag' => 'a',
                            'label' => Html::tag('i', null, ['class' => 'fa fa-fw fa-cogs']) . $item['label'],
                            'style' => 'cursor: pointer;',
                        ],
                    ],
                    'toggleButtonTemplate' => '{toggleButton}',
                ]),
                'encode' => false,
                'visible' => Yii::$app->user->can('server.manage-settings'),
            ];
        }

        return $items;
    }
}
