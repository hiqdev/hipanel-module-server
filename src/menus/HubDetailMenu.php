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

use hipanel\menus\AbstractDetailMenu;
use hipanel\widgets\AjaxModalWithTemplatedButton;
use Yii;
use yii\helpers\Html;

class HubDetailMenu extends AbstractDetailMenu
{
    public $model;

    public function items(): array
    {
        if ($this->model->isDeleted()) {
            return [
                'restore' => [
                    'label' => AjaxModalWithTemplatedButton::widget([
                        'ajaxModalOptions' => [
                            'id' => "monitoring-settings-modal-{$this->model->id}",
                            'bulkPage' => true,
                            'header' => Html::tag('h4', Yii::t('hipanel:server:hub', 'Restore'), ['class' => 'modal-title']),
                            'scenario' => 'default',
                            'actionUrl' => ['restore-modal', 'id' => $this->model->id],
                            'handleSubmit' => ['restore', 'id' => $this->model->id],
                            'toggleButton' => [
                                'tag' => 'a',
                                'label' => Html::tag('span', Html::tag('i', null, ['class' => 'fa fa-fw fa-history']), ['class' => 'pull-right']) . Yii::t('hipanel:server:hub', 'Restore'),
                                'style' => 'cursor: pointer;',
                            ],
                        ],
                        'toggleButtonTemplate' => '{toggleButton}',
                    ]),
                    'encode' => false,
                    'visible' => Yii::$app->user->can('server.manage-settings'),
                ],
            ];
        }

        $actions = HubActionsMenu::create(['model' => $this->model])->items();
        $items = array_merge($actions, [
            'delete' => [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@hub/delete', 'id' => $this->model->id],
                'encode' => false,
                'visible' => Yii::$app->user->can('hub.delete'),
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel', 'Are you sure you want to delete this item?'),
                        'method' => 'POST',
                        'pjax' => '0',
                    ],
                ],
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
                            'label' => Html::tag('span', Html::tag('i', null, ['class' => 'fa fa-fw fa-area-chart']), ['class' => 'pull-right']) . Yii::t('hipanel:server', 'Monitoring properties'),
                            'style' => 'cursor: pointer;',
                        ],
                    ],
                    'toggleButtonTemplate' => '{toggleButton}',
                ]),
                'encode' => false,
                'visible' => Yii::$app->user->can('server.manage-settings'),
            ],
        ]);
        unset($items['view']);

        return $items;
    }
}
