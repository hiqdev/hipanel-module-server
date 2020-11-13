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

use hipanel\helpers\Url;
use hipanel\modules\client\ClientWithCounters;
use hiqdev\yii2\menus\Menu;
use Yii;

class DashboardItem extends Menu
{
    protected ClientWithCounters $clientWithCounters;

    public function __construct(ClientWithCounters $clientWithCounters, $config = [])
    {
        $this->clientWithCounters = $clientWithCounters;
        parent::__construct($config);
    }

    public function items()
    {
        return Yii::$app->user->can('server.read') ? [
            'server' => [
                'label' => $this->render('dashboardItem', array_merge($this->clientWithCounters->getWidgetData('server'), [
                    'route' => Url::toRoute('@server/index'),
                ])),
                'encode' => false,
            ],
        ] : [];
    }
}
