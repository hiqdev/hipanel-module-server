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

class HubDetailMenu extends AbstractDetailMenu
{
    public $model;

    public function items()
    {
        $actions = HubActionsMenu::create(['model' => $this->model])->items();
        unset($actions['view']);

        return $actions;
    }
}
