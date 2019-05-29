<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;


class ConfigController extends CrudController
{
    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
        ]);
    }
}
