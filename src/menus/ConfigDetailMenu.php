<?php

namespace hipanel\modules\server\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\stock\widgets\HardwareSettingsButton;

class ConfigDetailMenu extends AbstractDetailMenu
{
    public $model;

    public function items()
    {
        $items = ConfigActionsMenu::create(['model' => $this->model])->items();
        $items[] = [
            'label' => HardwareSettingsButton::widget(['id' => $this->model->id, 'type' => 'config']),
            'encode' => false,
        ];

        unset($items['view']);

        return $items;
    }
}
