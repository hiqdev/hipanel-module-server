<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\base\CrudController;
use hipanel\modules\server\models\Change;
use Yii;

class PreOrderController extends CrudController
{
    public static function modelClassName()
    {
        return Change::class;
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'data' => function ($action) {
                    return [
                        'states' => $action->controller->getStates(),
                    ];
                },
            ],
        ];
    }

    public function getStates()
    {
        return $this->getRefs('state,change', [], 'hipanel/server');
    }

}
