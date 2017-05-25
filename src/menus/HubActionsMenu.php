<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\menus;

use Yii;

class HubActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@hub/update', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'Options'),
                'icon' => 'fa-cogs',
                'url' => ['@hub/options', 'id' => $this->model->id],
            ]
        ];
    }
}
