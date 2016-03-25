<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\base\CrudController;
use Yii;
use yii\base\Event;

class SwitchGraphController extends CrudController
{
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
                    'formName' => ''
                ]
            ],
        ];
    }
}
