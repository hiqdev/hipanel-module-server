<?php
declare(strict_types=1);

namespace hipanel\modules\server\controllers;

use hipanel\actions\ComboSearchAction;
use hipanel\base\CrudController;

class DeviceController extends CrudController
{
    public function actions()
    {
        return array_merge(parent::actions(), [
            'search' => [
                'class' => ComboSearchAction::class,
            ],
        ]);
    }
}
