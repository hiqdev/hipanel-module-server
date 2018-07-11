<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\menus;

class HubDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        $actions = HubActionsMenu::create(['model' => $this->model])->items();
        $items = array_merge($actions, []);
        unset($items['view']);

        return $items;
    }
}
