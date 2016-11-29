<?php

namespace hipanel\modules\server\menus;

use hiqdev\menumanager\Menu;
use Yii;

class ServerDetailMenu extends Menu
{
    public $model;

    public $blockReasons;

    public function items()
    {
        $actions = ServerActionsMenu::create(['model' => $this->model])->items();
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
                'label' => $this->renderView('_reset-password', ['model' => $this->model]),
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
                'label' => $this->renderView('_block', ['model' => $this->model, 'blockReasons' => $this->blockReasons]),
                'visible' => Yii::$app->user->can('support') && Yii::$app->user->not($this->model->client_id),
                'encode' => false,
            ],
            [
                'label' => $this->renderView('_delete', ['model' => $this->model]),
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
