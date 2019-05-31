<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use Yii;

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
            'create' => [
                'class' => SmartCreateAction::class,
                'scenario' => 'create',
                'success' => Yii::t(
                    'hipanel:server:config',
                    'The configuration has been successfully created'
                ),
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'update',
                'success' => Yii::t(
                    'hipanel:server:config',
                    'The configuration has been successfully updated'
                ),
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t(
                    'hipanel:server:config',
                    'The configuration has been deleted'
                ),
                'error' => Yii::t(
                    'hipanel:server:config',
                    'Failed to delete the configuration'
                ),
            ],
            'enable' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t(
                    'hipanel:server:config',
                    'The configuration has been enabled'
                ),
            ],
            'disable' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t(
                    'hipanel:server:config',
                    'The configuration has been disabled'
                ),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ]);
    }
}
