<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use yii\base\Event;

class SwitchGraphController extends CrudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    '*' => 'server.read',
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'view' => [
                'class' => IndexAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->joinWith('images');
                    $dataProvider->query->joinWith('server');
                    $dataProvider->pagination = false;
                },
                'collection' => [
                    'formName' => '',
                ],
            ],
        ];
    }
}
