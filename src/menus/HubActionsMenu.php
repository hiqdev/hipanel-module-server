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

use Yii;

class HubActionsMenu extends \hiqdev\yii2\menus\Menu
{
    public $model;

    public function items()
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@hub/view', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('hub.read'),
            ],
            [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@hub/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('hub.update'),
            ],
            [
                'label' => Yii::t('hipanel:server:hub', 'Options'),
                'icon' => 'fa-cogs',
                'url' => ['@hub/options', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('hub.update'),
            ],
        ];
    }
}
