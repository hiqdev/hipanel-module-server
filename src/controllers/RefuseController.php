<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\base\CrudController;
use hipanel\modules\server\models\Change;
use Yii;
use yii\base\Event;
use yii\filters\AccessControl;

class RefuseController extends CrudController
{
    public static function modelClassName()
    {
        return Change::class;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'roles'   => ['manage'],
                    ],
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
            'bulk-approve' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'approve',
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
                'scenario' => 'approve',
                'view' => '_bulk-operation',
                'findOptions' => [
                    'state' => Change::STATE_NEW,
                ],
                'data' => function ($action, $data) {
                    return [
                        'bulkOp' => array_merge($data, [
                            'scenario' => 'approve',
                            'submitButton' => Yii::t('hipanel:finance:change', 'Approve'),
                            'submitButtonOptions' => ['class' => 'btn btn-success'],
                        ]),
                    ];
                },
            ],
            'bulk-reject' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'reject',
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
                'scenario' => 'reject',
                'view' => '_bulk-operation',
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
