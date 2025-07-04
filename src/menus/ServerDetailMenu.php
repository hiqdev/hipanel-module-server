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

use hipanel\widgets\AuditButton;
use hipanel\widgets\BlockModalButton;
use hipanel\widgets\SimpleOperation;
use Yii;

class ServerDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;
    public $blockReasons;

    public function items()
    {
        $actions = ServerActionsMenu::create([
            'model' => $this->model,
            'isDetailedView' => true,
        ])->items();

        $user = Yii::$app->user;

        $items = array_merge($actions, [
            [
                'label' => Yii::t('hipanel:server', 'Resources'),
                'icon' => 'fa-area-chart',
                'url' => ['@consumption/view', 'id' => $this->model->id],
                'visible' => $user->can('consumption.read'),
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
                'visible' => $this->model->isPwChangeSupported() && $user->can('server.control-system'),
                'encode' => false,
            ],
            [
                'label' => BlockModalButton::widget(['model' => $this->model]),
                'visible' => ($user->can('server.enable-block') || ($user->can('server.disable-block')))
                    && $user->not($this->model->client_id)
                    && !$this->model->isDeleted(),
                'encode' => false,
            ],
            [
                'label' => SimpleOperation::widget([
                    'model' => $this->model,
                    'scenario' => 'delete',
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
                'visible' => Yii::$app->user->can('server.delete') && !$this->model->isDeleted(),
            ],
            [
                'label' => AuditButton::widget(['model' => $this->model]),
                'encode' => false,
            ]
        ]);
        unset($items['view']);

        return $items;
    }

    public function getViewPath()
    {
        return '@vendor/hiqdev/hipanel-module-server/src/views/server';
    }
}
