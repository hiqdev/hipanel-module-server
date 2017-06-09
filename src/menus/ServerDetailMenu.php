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

use hipanel\widgets\SimpleOperation;
use hipanel\widgets\BlockModalButton;
use Yii;

class ServerDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public $blockReasons;

    public function items()
    {
        $actions = ServerActionsMenu::create([
            'model' => $this->model,
        ])->items();

        $user = Yii::$app->user;

        $items = array_merge($actions, [
            [
                'label' => Yii::t('hipanel:server', 'Renew server'),
                'icon' => 'fa-forward',
                'url' => ['add-to-cart-renewal', 'model_id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => SimpleOperation::widget([
                    'model' => $this->model,
                    'scenario' => 'reset-password',
                    'buttonLabel' => '<i class="fa fa-refresh"></i>' . Yii::t('hipanel:server', 'Reset root password'),
                    'buttonClass' => '',
                    'body' => Yii::t('hipanel:server', 'Are you sure you want to reset the root password on {name} server? You will get your new root password on the e-mail.'),
                    'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm root password resetting'),
                    'modalHeaderOptions' => ['class' => 'label-danger'],
                    'modalFooterLabel' => Yii::t('hipanel:server', 'Reset root password'),
                    'modalFooterLoading' => Yii::t('hipanel:server', 'Resetting...'),
                    'modalFooterClass' => 'btn btn-danger',
                ]),
                'visible' => $this->model->isPwChangeSupported(),
                'encode' => false,
            ],
            [
                'label' => Yii::t('hipanel:server', 'Resources usage graphs'),
                'icon' => 'fa-signal',
                'url' => ['@rrd/view', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel:server', 'Switch graphs'),
                'icon' => 'fa-area-chart',
                'url' => ['@switch-graph/view', 'id' => $this->model->id],
            ],
            [
                'label' => BlockModalButton::widget(['model' => $this->model]),
                'visible' => $user->can('support') && $user->not($this->model->client_id),
                'encode' => false,
            ],
            [
                'label' => SimpleOperation::widget([
                    'model' => $this->model,
                    'scenario' => 'delete',
                    'skipCheckOperable' => true,
                    'buttonLabel' => '<i class="fa fa-fw fa-trash-o"></i>' . Yii::t('hipanel', 'Delete'),
                    'buttonClass' => '',
                    'body' => Yii::t('hipanel:server', 'Are you sure you want to delete server {name}? You will loose everything!', ['name' => $this->model->name]),
                    'modalHeaderLabel' => Yii::t('hipanel:server', 'Confirm server deleting'),
                    'modalHeaderOptions' => ['class' => 'label-danger'],
                    'modalFooterLabel' => Yii::t('hipanel:server', 'Delete server'),
                    'modalFooterLoading' => Yii::t('hipanel:server', 'Deleting server'),
                    'modalFooterClass' => 'btn btn-danger',
                ]),
                'encode' => false,
                'visible' => Yii::$app->user->can('support'),
            ],
        ]);

        return $items;
    }

    public function getViewPath()
    {
        return '@vendor/hiqdev/hipanel-module-server/src/views/server';
    }
}
