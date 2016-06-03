<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\Action;
use hipanel\actions\IndexAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\base\CrudController;
use hipanel\modules\server\models\Change;
use Yii;
use yii\base\Event;

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
                'findOptions' => ['state' => 'new', 'class' => 'serverBuy'],
                'data' => function ($action) {
                    return [
                        'states' => $action->controller->getStates(),
                    ];
                },
            ],
            'bulk-approve' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'approve',
                'success' => Yii::t('hipanel/server', 'Hosting accounts were blocked successfully'),
                'error' => Yii::t('hipanel/server', 'Error during the hosting accounts blocking'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $comment = Yii::$app->request->post('comment');
                    if (!empty($type)) {
                        foreach ($action->collection->models as $model) {
                            $model->setAttribute('comment', $comment);

                        }
                    }
                },
            ],
            'bulk-approve-modal' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'approve',
                'view' => '_bulkApprove',
            ],
        ];
    }

    public function getStates()
    {
        return $this->getRefs('state,change', [], 'hipanel/server');
    }

}
