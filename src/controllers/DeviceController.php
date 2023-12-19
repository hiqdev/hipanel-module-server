<?php

declare(strict_types=1);

namespace hipanel\modules\server\controllers;

use hipanel\actions\ComboSearchAction;
use hipanel\actions\RedirectAction;
use hipanel\base\CrudController;
use hipanel\modules\server\actions\SetDevicePropertiesAction;
use hipanel\modules\server\models\DeviceProperties;
use hiqdev\hiart\Collection;

class DeviceController extends CrudController
{
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'search' => [
                'class' => ComboSearchAction::class,
            ],
            'set-properties' => [
                'class' => SetDevicePropertiesAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new DeviceProperties(),
                    'scenario' => 'set-properties',
                ],
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => fn(): string => $this->request->getReferrer(),
                    ],
                ],
            ],
        ]);
    }
}
