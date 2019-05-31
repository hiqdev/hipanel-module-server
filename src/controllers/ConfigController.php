<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\modules\server\forms\ConfigForm;
use hiqdev\hiart\Collection;
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
                    'The configuration was successfully created'
                ),
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'update',
                'success' => Yii::t(
                    'hipanel:server:config',
                    'The configuration was successfully updated'
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
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ]);
    }
}
