<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\controllers;

use hipanel\actions\Action;
use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\ProxyAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderAction;
use hipanel\actions\RenderJsonAction;
use hipanel\actions\RequestStateAction;
use hipanel\actions\SearchAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\modules\finance\models\Tariff;
use hipanel\modules\server\cart\ServerRenewProduct;
use hipanel\modules\server\helpers\ServerHelper;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\models\ServerUseSearch;
use hiqdev\hiart\ResponseErrorException;
use hiqdev\yii2\cart\actions\AddToCartAction;
use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ServerController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'findOptions' => ['with_requests' => true, 'with_discounts' => true],
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->joinWith('ips');

                    $dataProvider->query
                        ->andWhere(['with_ips' => 1])
                        ->andWhere(['with_tariffs' => 1])
                        ->andWhere(['with_switches' => 1])
                        ->andWhere(['with_requests' => 1])
                        ->andWhere(['with_discounts' => 1])
                        ->select(['*']);
                },
                'filterStorageMap' => [
                    'name_like' => 'server.server.name',
                    'ips' => 'hosting.ip.ip_in',
                    'state' => 'server.server.state',
                    'client_id' => 'client.client.id',
                    'seller_id' => 'client.client.seller_id',
                ],
            ],
            'search' => [
                'class' => SearchAction::class,
            ],
            'view' => [
                'class' => ViewAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->joinWith('uses');
                    $dataProvider->query->joinWith('ips');

                    // TODO: ipModule is not wise yet. Redo
                    $dataProvider->query
                        ->andWhere(['with_requests' => 1])
                        ->andWhere(['show_deleted' => 1])
                        ->andWhere(['with_discounts' => 1])
                        ->andWhere(['with_uses' => 1])
                        ->andWhere(['with_ips' => 1])
                        ->select(['*']);
                },
                'data' => function ($action) {
                    /**
                     * @var Action $action
                     * @var self $controller
                     * @var Server $model
                     */
                    $controller = $action->controller;
                    $model = $action->getModel();
                    $model->vnc = $controller->getVNCInfo($model);

                    $panels = $controller->getPanelTypes();

                    $tariff = Yii::$app->cache->getAuthTimeCached(3600, [$model->tariff_id], function ($tariff_id) {
                        return Tariff::find()->where([
                            'id' => $tariff_id,
                            'show_final' => true,
                            'show_deleted' => true,
                            'with_resources' => true,
                        ])->joinWith('resources')->one();
                    });

                    $ispSupported = false;
                    if ($tariff !== null) {
                        foreach ($tariff->getResources() as $resource) {
                            if ($resource->type === 'isp' && $resource->quantity > 0) {
                                $ispSupported = true;
                            }
                        }
                    }

                    $osimages = $controller->getOsimages($model);
                    $groupedOsimages = ServerHelper::groupOsimages($osimages, $ispSupported);

                    if ($model->isLiveCDSupported()) {
                        $osimageslivecd = $controller->getOsimagesLiveCd();
                    }

                    $blockReasons = $controller->getBlockReasons();

                    return compact([
                        'model',
                        'osimages',
                        'osimageslivecd',
                        'groupedOsimages',
                        'panels',
                        'blockReasons',
                    ]);
                },
            ],
            'requests-state' => [
                'class' => RequestStateAction::class,
                'model' => Server::class,
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Note changed'),
                'error' => Yii::t('hipanel:server', 'Failed to change note'),
            ],
            'set-label' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Internal note changed'),
                'error' => Yii::t('hipanel:server', 'Failed to change internal note'),
            ],
            'set-lock' => [
                'class' => RenderAction::class,
                'success' => Yii::t('hipanel:server', 'Record was changed'),
                'error' => Yii::t('hipanel:server', 'Error occurred'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class' => ProxyAction::class,
                        'action' => 'index',
                    ],
                ],
                'POST' => [
                    'save' => true,
                    'success' => [
                        'class' => RenderJsonAction::class,
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        },
                    ],
                ],
            ],
            'sale' => [
                'class' => SmartUpdateAction::class,
                'view' => '_saleModal',
                'POST' => [
                    'save' => true,
                    'success' => [
                        'class' => RenderJsonAction::class,
                        'return' => function ($action) {
                            return ['success' => !$action->collection->hasErrors()];
                        },
                    ],
                ],
            ],
            'enable-vnc' => [
                'class' => ViewAction::class,
                'view' => '_vnc',
                'data' => function ($action) {
                    $model = $action->getModel();
                    if ($model->canEnableVNC()) {
                        $model->vnc = $this->getVNCInfo($model, true);
                    }

                    return [];
                },
            ],
            'reboot' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Reboot task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the rebooting'),
            ],
            'reset' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Reset task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the resetting'),
            ],
            'shutdown' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Shutdown task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the shutting down'),
            ],
            'power-off' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Power off task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning power off'),
            ],
            'power-on' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Power on task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning power on'),
            ],
            'reset-password' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Root password reset task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the resetting root password'),
            ],
            'enable-block' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Server was blocked successfully'),
                'error' => Yii::t('hipanel:server', 'Error during the server blocking'),
            ],
            'disable-block' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Server was unblocked successfully'),
                'error' => Yii::t('hipanel:server', 'Error during the server unblocking'),
            ],
            'refuse' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'You have refused the service'),
                'error' => Yii::t('hipanel:server', 'Error during the refusing the service'),
            ],
            'enable-autorenewal' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Server renewal enabled successfully'),
                'error' => Yii::t('hipanel:server', 'Error during the renewing the service'),
            ],
            'reinstall' => [
                'class' => SmartUpdateAction::class,
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->osimage = Yii::$app->request->post('osimage');
                        $model->panel = Yii::$app->request->post('panel');
                    }
                },
                'success' => Yii::t('hipanel:server', 'Server reinstalling task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the server reinstalling'),
            ],
            'boot-live' => [
                'class' => SmartUpdateAction::class,
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->osimage = Yii::$app->request->post('osimage');
                    }
                },
                'success' => Yii::t('hipanel:server', 'Live CD booting task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the booting live CD'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'buy' => [
                'class' => RedirectAction::class,
                'url' => Yii::$app->params['orgUrl'],
            ],
            'add-to-cart-renewal' => [
                'class' => AddToCartAction::class,
                'productClass' => ServerRenewProduct::class,
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel:server', 'Server was deleted successfully'),
                'error' => Yii::t('hipanel:server', 'Failed to delete server'),
            ],
            'bulk-delete-modal' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'delete',
                'view' => '_bulkDelete',
            ],
            'bulk-enable-block' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'enable-block',
                'success' => Yii::t('hipanel:server', 'Servers were blocked successfully'),
                'error' => Yii::t('hipanel:server', 'Error during the servers blocking'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $type = Yii::$app->request->post('type');
                    $comment = Yii::$app->request->post('comment');
                    if (!empty($type)) {
                        foreach ($action->collection->models as $model) {
                            $model->setAttributes([
                                'type' => $type,
                                'comment' => $comment,
                            ]);
                        }
                    }
                },
            ],
            'bulk-enable-block-modal' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'enable-block',
                'view' => '_bulkEnableBlock',
                'data' => function ($action, $data) {
                    return array_merge($data, [
                        'blockReasons' => $this->getBlockReasons(),
                    ]);
                },
            ],
            'bulk-disable-block' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'disable-block',
                'success' => Yii::t('hipanel:server', 'Servers were unblocked successfully'),
                'error' => Yii::t('hipanel:server', 'Error during the servers unblocking'),
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
            'bulk-disable-block-modal' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'disable-block',
                'view' => '_bulkDisableBlock',
            ],
            'set-orientation' => [
                'class' => OrientationAction::class,
                'allowedRoutes' => [
                    '@server/index'
                ]
            ]

        ];
    }

    /**
     * Gets info of VNC on the server.
     *
     * @param Server $model
     * @param bool $enable
     * @return array
     * @throws ResponseErrorException
     */
    public function getVNCInfo($model, $enable = false)
    {
        $vnc = ['endTime' => strtotime('+8 hours', strtotime($model->statuses['serverEnableVNC']))];
        if ($model->canEnableVnc() && $vnc['endTime'] > time() || $enable) {
            try {
                $vnc = ArrayHelper::merge($vnc, Server::perform('enable-VNC', ['id' => $model->id]));
                $vnc['enabled'] = true;
            } catch (ResponseErrorException $e) {
                if ($e->getMessage() !== 'vds_has_tasks') { // expected error, that could be skipped
                    throw $e;
                }
                $vnc['enabled'] = false;
            }
        }

        return $vnc;
    }

    public function actionDrawChart()
    {
        $post = Yii::$app->request->post();
        if (!in_array($post['type'], ['traffic', 'bandwidth'], true)) {
            throw new NotFoundHttpException();
        }

        $searchModel = new ServerUseSearch();
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;
        $dataProvider->query->options = ['scenario' => 'get-uses'];
        $dataProvider->query->andWhere($post);
        $models = $dataProvider->getModels();

        list($labels, $data) = ServerHelper::groupUsesForChart($models);

        return $this->renderAjax('_' . $post['type'] . '_consumption', [
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * Gets OS images.
     *
     * @param Server $model
     * @throws NotFoundHttpException
     * @return array
     */
    protected function getOsimages(Server $model = null)
    {
        if ($model !== null) {
            $type = $model->type;
        } else {
            $type = null;
        }

        $models = ServerHelper::getOsimages($type);

        if ($models === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $models;
    }

    protected function getOsimagesLiveCd()
    {
        $models = Yii::$app->cache->getTimeCached(3600, [true], function ($livecd) {
            return Osimage::findAll(['livecd' => $livecd]);
        });

        if ($models !== null) {
            return $models;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getPanelTypes()
    {
        return ServerHelper::getPanels();
    }

    public function actionIsOperable($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['id' => $id, 'result' => false];

        if ($server = Server::find()->where(['id' => $id])->one()) {
            $result['result'] = $server->isOperable();
        }

        return $result;
    }
}
