<?php
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
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\server\models\Change;
use Yii;
use yii\base\Event;

class RefuseController extends CrudController
{
    public static function modelClassName()
    {
        return Change::class;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    '*' => 'manage',
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'findOptions' => ['state' => 'new', 'class' => 'server'],
                'data' => function ($action) {
                    return [
                        'states' => Change::getStates(),
                    ];
                },
            ],
            'approve' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Server refuse was approved successfully'),
                'error' => Yii::t('hipanel:server', 'Error occurred during server refuse approving'),
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
                    foreach ($action->collection->models as $model) {
                        $model->setAttribute('comment', $comment);
                    }
                },
            ],
            'bulk-approve-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => '_bulkApprove',
                'findOptions' => [
                    'state' => Change::STATE_NEW,
                ],
            ],
            'reject' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Server refuse was rejected successfully'),
                'error' => Yii::t('hipanel:server', 'Error occurred during server refuse rejecting'),
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
                    foreach ($action->collection->models as $model) {
                        $model->setAttribute('comment', $comment);
                    }
                },
            ],
            'bulk-reject-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => '_bulkReject',
                'findOptions' => [
                    'state' => Change::STATE_NEW,
                ],
                'data' => function ($action, $data) {
                    return [
                        'bulkOp' => array_merge($data, [
                            'scenario' => 'reject',
                            'submitButton' => Yii::t('hipanel:finance:change', 'Reject'),
                            'submitButtonOptions' => ['class' => 'btn btn-danger'],
                        ]),
                    ];
                },
            ],
        ];
    }
}
