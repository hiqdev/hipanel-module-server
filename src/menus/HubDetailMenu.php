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

use Yii;

class HubDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        $actions = HubActionsMenu::create(['model' => $this->model])->items();
        $items = array_merge($actions, [
            'assign-switches' => [
                'label' => Yii::t('hipanel:server', 'Switches'),
                'icon' => 'fa-plug',
                'url' => ['@hub/assign-switches', 'id' => $this->model->id],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
        ]);
        unset($items['view']);

        return $items;
    }
}
