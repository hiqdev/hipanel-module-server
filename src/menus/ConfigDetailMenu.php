<?php

namespace hipanel\modules\server\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\server\models\Config;
use hipanel\modules\stock\widgets\HardwareSettingsButton;
use Yii;

class ConfigDetailMenu extends AbstractDetailMenu
{
    public Config $model;

    public function items(): array
    {
        $items = ConfigActionsMenu::create(['model' => $this->model])->items();
        $items[] = [
            'label' => HardwareSettingsButton::widget(['id' => $this->model->id, 'type' => 'config']),
            'encode' => false,
            'visible' => Yii::$app->user->can('model.update'),
        ];

        unset($items['view']);

        return $items;
    }
}
