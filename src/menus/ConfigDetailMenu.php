<?php

namespace hipanel\modules\server\menus;

use Yii;

class ConfigDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        $actions = ConfigActionsMenu::create(['model' => $this->model])->items();
        $items = array_merge($actions, [
            'delete' => [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@config/delete', 'id' => $this->model->id],
                'encode' => false,
                'visible' => Yii::$app->user->can('config.delete'),
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel', 'Are you sure you want to delete this item?'),
                        'method' => 'POST',
                        'pjax' => '0',
                    ],
                ],
            ],
        ]);
        unset($items['view']);

        return $items;
    }
}
