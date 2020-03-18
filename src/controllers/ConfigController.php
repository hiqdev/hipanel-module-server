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
use hipanel\modules\server\cart\ServerOrderDedicatedProduct;
use hiqdev\yii2\cart\ShoppingCart;
use Yii;
use yii\web\Response;

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
                'on beforeSave' => function ($event) {
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        if ($id = Yii::$app->request->get('id')) {
                            $model->id = $id;
                        }
                    }
                },
            ],
            'disable' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t(
                    'hipanel:server:config',
                    'The configuration has been disabled'
                ),
                'on beforeSave' => function ($event) {
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        if ($id = Yii::$app->request->get('id')) {
                            $model->id = $id;
                        }
                    }
                },
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ]);
    }

    public function actionReserve(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        /** @var ShoppingCart $cart */
        $cart = Yii::$app->getModule('cart')->getCart();

        $position = $cart->getPositionById(Yii::$app->request->post('reservation_id', null));
        if ($position === null || !$position instanceof ServerOrderDedicatedProduct) {
            return ['success' => false];
        }

        $position->reserve();
        $cart->saveToSession();
        return [
            'success' => true,
            'expirationTime' => $position->expirationTime->format(DATE_ATOM),
        ];
    }
}
