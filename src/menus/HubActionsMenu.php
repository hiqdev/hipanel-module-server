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
use yii\helpers\Html;
use Yii;

class HubActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public $model;

    public function items()
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@hub/view', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('hub.read'),
            ],
            [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@hub/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('hub.update'),
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'Options'),
                'icon' => 'fa-cogs',
                'url' => ['@hub/options', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('hub.update'),
            ],
            'assign-switches' => [
                'label' => Yii::t('hipanel:server', 'Switches'),
                'icon' => 'fa-plug',
                'url' => ['@hub/assign-switches', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            'monitoring-settings' => [
                'url' => ['monitoring-settings', 'id' => $this->model->id],
                'icon' => 'fa-cogs',
                'label' => AjaxModalWithTemplatedButton::widget([
                    'ajaxModalOptions' => [
                        'id' => "{$key}-modal-{$this->model->id}",
                        'bulkPage' => true,
                        'header' => Html::tag('h4', Yii::t('hipanel:server', 'Monitoring properties'), ['class' => 'modal-title']),
                        'scenario' => 'default',
                        'actionUrl' => ['monitoring-settings', 'id' => $this->model->id],
                        'handleSubmit' => ['monitoring-settings', 'id' => $this->model->id],
                        'toggleButton' => [
                            'tag' => 'a',
                            'label' => Yii::t('hipanel:server', 'Monitoring properties'),
                        ],
                    ],
                    'toggleButtonTemplate' => '{toggleButton}',
                ]),
                'encode' => false,
                'visible' => Yii::$app->user->can('server.manage-settings'),
            ],
        ];
    }
}
