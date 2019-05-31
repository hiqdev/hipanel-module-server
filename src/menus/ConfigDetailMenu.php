<?php

namespace hipanel\modules\server\menus;

use Yii;

class ConfigDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        $items = ConfigActionsMenu::create(['model' => $this->model])->items();

        unset($items['view']);

        return $items;
    }
}
