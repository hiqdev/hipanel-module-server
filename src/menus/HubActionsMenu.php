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

use hipanel\widgets\AjaxModalWithTemplatedButton;
use hiqdev\yii2\menus\Menu;
use yii\helpers\Html;
use Yii;
use yii\web\JsExpression;

class HubActionsMenu extends Menu
{
    public $model;

    public function items(): array
    {
        $user = Yii::$app->user;
        $onlyView = [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@hub/view', 'id' => $this->model->id],
                'visible' => $user->can('hub.read'),
            ],
        ];
        if ($this->model->isDeleted()) {
            return $onlyView;
        }

        $actions = [
            [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@hub/update', 'id' => $this->model->id],
                'visible' => $user->can('hub.update') && !$this->model->isServer(),
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'Options'),
                'icon' => 'fa-cogs',
                'url' => ['@hub/options', 'id' => $this->model->id],
                'visible' => $user->can('hub.update') && !$this->model->isServer(),
            ],
            'assign-switches' => [
                'label' => Yii::t('hipanel:server', 'Assgin hubs'),
                'icon' => 'fa-plug',
                'url' => ['@hub/assign-hubs', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
                'visible' => $user->can('hub.read'),
            ],
            'monitoring-settings' => [
                'label' => AjaxModalWithTemplatedButton::widget([
                    'ajaxModalOptions' => [
                        'id' => "monitoring-settings-modal-{$this->model->id}",
                        'bulkPage' => true,
                        'header' => Html::tag('h4', Yii::t('hipanel:server', 'Monitoring properties'), ['class' => 'modal-title']),
                        'scenario' => 'default',
                        'actionUrl' => ['monitoring-settings', 'id' => $this->model->id],
                        'handleSubmit' => ['monitoring-settings', 'id' => $this->model->id],
                        'toggleButton' => [
                            'tag' => 'a',
                            'label' => Html::tag('i', null, ['class' => 'fa fa-fw fa-area-chart']) . '&nbsp;' . Yii::t('hipanel:server', 'Monitoring properties'),
                            'style' => 'cursor: pointer;',
                            'onClick' => new JsExpression("$(this).parents('.menu-button').find('.popover').popover('hide');"),
                        ],
                    ],
                    'toggleButtonTemplate' => '{toggleButton}',
                ]),
                'encode' => false,
                'visible' => $user->can('server.manage-settings'),
            ],
        ];

        return array_merge($onlyView, $actions);
    }
}
