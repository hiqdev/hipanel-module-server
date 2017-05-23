<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;

class HubController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'data' => function () {
                    return [
                        'types' => $this->getTypes(),
                    ];
                }
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
        ];
    }

    protected function getTypes()
    {
        return Ref::getList('type,device,switch', 'hipanel:server:hub');
    }
}