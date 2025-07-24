<?php

declare(strict_types=1);
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
use hipanel\actions\RenderAction;
use hipanel\actions\SearchAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use yii\base\Event;
use yii\web\NotFoundHttpException;

class RrdController extends CrudController
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
                    /** @var SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->joinWith('images');
                    $dataProvider->query->joinWith('server');
                    $dataProvider->pagination = false;
                },
                'data' => function (RenderAction $action, array $data): array {
                    $models = $data['dataProvider']->getModels();
                    if (empty($models)) {
                        throw new NotFoundHttpException('Server not found');
                    }

                    return array_merge($data, [
                        'searchModel' => $data['model'],
                        'models' => $models,
                        'model' => reset($models),
                    ]);

                },
                'collection' => [
                    'formName' => '',
                ],
            ],
        ];
    }
}
