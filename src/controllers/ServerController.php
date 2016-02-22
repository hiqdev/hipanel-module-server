<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\ProxyAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderAction;
use hipanel\actions\RenderJsonAction;
use hipanel\actions\RequestStateAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\modules\finance\models\Tariff;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\models\Server;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ServerController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'findOptions' => ['with_requests' => true, 'with_discounts' => true],
                'data' => function ($action) {
                    return [
                        'states' => $action->controller->getStates()
                    ];
                },
                'filterStorageMap' => [
                    'name_like' => 'server.server.name',
                    'ips' => 'hosting.ip.ip_in',
                    'state' => 'server.server.state',
                    'client_id' => 'client.client.id',
                    'seller_id' => 'client.client.seller_id',
                ]
            ],
            'view' => [
                'class'       => ViewAction::class,
                'findOptions' => [
                    'with_requests' => true,
                    'show_deleted' => true,
                    'with_discounts' => true,
                    'with_uses' => true,
                ],
                'data'        => function ($action) {
                    /**
                     * @var $controller $this
                     * @var $model Server
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
                    $grouped_osimages = $controller->getGroupedOsimages($osimages, $ispSupported);

                    if ($model->isLiveCDSupported()) {
                        $osimageslivecd = $controller->getOsimagesLiveCd();
                    }

                    $blockReasons = $controller->getBlockReasons();

                    return compact(['model', 'osimages', 'osimageslivecd', 'grouped_osimages', 'panels', 'blockReasons']);
                },
            ],
            'requests-state' => [
                'class' => RequestStateAction::className(),
                'model' => Server::className()
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Note changed'),
                'error' => Yii::t('hipanel/server', 'Failed to change note'),
            ],
            'set-label' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Internal note changed'),
                'error' => Yii::t('hipanel/server', 'Failed to change internal note'),
            ],
            'set-lock' => [
                'class' => RenderAction::class,
                'success' => Yii::t('hipanel/server', 'Record was changed'),
                'error' => Yii::t('hipanel/server', 'Error occurred'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class' => ProxyAction::class,
                        'action' => 'index'
                    ]
                ],
                'POST' => [
                    'save' => true,
                    'success' => [
                        'class' => RenderJsonAction::class,
                        'return' => function ($action) {
                            /** @var \hipanel\actions\Action $action */
                            return $action->collection->models;
                        }
                    ]
                ],
            ],
            'enable-vnc' => [
                'class' => ViewAction::class,
                'data' => function ($action) {
                    $model = $action->getModel();
                    $model->checkOperable();
                    $model->vnc = $action->controller->getVNCInfo($model, true);
                    return [];
                }
            ],
            'reboot' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Reboot task has been successfully added to queue'),
                'error' => Yii::t('hipanel/server', 'Error during the rebooting'),
            ],
            'reset' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Reset task has been successfully added to queue'),
                'error' => Yii::t('hipanel/server', 'Error during the resetting'),
            ],
            'shutdown' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Shutdown task has been successfully added to queue'),
                'error' => Yii::t('hipanel/server', 'Error during the shutting down'),
            ],
            'power-off' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Power off task has been successfully added to queue'),
                'error' => Yii::t('hipanel/server', 'Error during the turning power off'),
            ],
            'power-on' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Power on task has been successfully added to queue'),
                'error' => Yii::t('hipanel/server', 'Error during the turning power on'),
            ],
            'reset-password' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Root password reset task has been successfully added to queue'),
                'error' => Yii::t('hipanel/server', 'Error during the resetting root password'),
            ],
            'enable-block' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Server was blocked successfully'),
                'error' => Yii::t('hipanel/server', 'Error during the server blocking'),
            ],
            'disable-block' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Server was unblocked successfully'),
                'error' => Yii::t('hipanel/server', 'Error during the server unblocking'),
            ],
            'refuse' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'You have refused the service'),
                'error' => Yii::t('hipanel/server', 'Error during the refusing the service'),
            ],
            'enable-autorenewal' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel/server', 'Server renewal enabled successfully'),
                'error' => Yii::t('hipanel/server', 'Error during the renewing the service'),
            ],
            'reinstall' => [
                'class' => SmartUpdateAction::class,
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->osmage = Yii::$app->request->post('osimage');
                        $model->panel = Yii::$app->request->post('panel');
                    }
                },
                'success' => Yii::t('hipanel/server', 'Server reinstalling task has been successfully added to queue'),
                'error' => Yii::t('hipanel/server', 'Error during the server reinstalling'),
            ],
            'boot-live' => [
                'class' => SmartUpdateAction::class,
                'on beforeSave' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    foreach ($action->collection->models as $model) {
                        $model->osmage = Yii::$app->request->post('osimage');
                    }
                },
                'success' => Yii::t('hipanel/server', 'Live CD booting task has been successfully added to queue'),
                'error' => Yii::t('hipanel/server', 'Error during the booting live CD'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'buy' => [
                'class' => RedirectAction::class,
                'url' => Yii::$app->params['orgUrl'],
            ],
        ];
    }

    /**
     * Gets info of VNC on the server
     *
     * @param Server $model
     * @param bool $enable
     *
     * @return array
     */
    public function getVNCInfo($model, $enable = false)
    {
        $vnc['endTime'] = strtotime('+8 hours', strtotime($model->statuses['serverEnableVNC']));
        if (($vnc['endTime'] > time() || $enable) && $model->isOperable()) {
            $vnc['enabled'] = true;
            $vnc = ArrayHelper::merge($vnc, Server::perform('EnableVNC', ['id' => $model->id]));
        }

        return $vnc;
    }

    /**
     * Gets OS images
     *
     * @param Server $model
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getOsimages(Server $model = null)
    {
        $condition = [];
        if ($model !== null) {
            $condition['type'] = $model->type;
        }

        $models = Yii::$app->cache->getTimeCached(3600, [$condition], function ($condition) {
            return Osimage::find()->where($condition)->all();
        });

        if ($models !== null) {
            return $models;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
        return Ref::getList('type,panel');
    }

    protected function getStates()
    {
        return Ref::getList('state,device');
    }

    /**
     * Generates array of osimages data, grouped by different fields to display on the website
     *
     * @param $images array of osimages models to be proceed
     * @param bool $ispSupported
     * @return array
     */
    protected function getGroupedOsimages($images, $ispSupported = false)
    {
        $softpacks = [];
        $oses = [];
        $vendors = [];
        foreach ($images as $image) {
            /** @var Osimage $image */
            $os = $image->os;
            $name = $image->getFullOsName();
            $panel = $image->getPanelName();
            $system = $image->getFullOsName('');
            $softpack_name = $image->getSoftPackName();
            $softpack = $image->getSoftPack();

            if (!array_key_exists($system, $oses)) {
                $vendors[$os]['name'] = $os;
                $vendors[$os]['oses'][$system] = $name;
                $oses[$system] = ['vendor' => $os, 'name' => $name];
            }

            if ($panel != 'isp' || ($panel == 'isp' && $ispSupported)) {
                $data = [
                    'name' => $softpack_name,
                    'description' => preg_replace('/^ISPmanager - /', '', $softpack['description']),
                    'osimage' => $image->osimage
                ];

                if ($softpack['soft']) {
                    $html_desc = [];
                    foreach ($softpack['soft'] as $soft => $soft_info) {
                        $soft_info['description'] = preg_replace('/,([^\s])/', ', $1', $soft_info['description']);

                        $html_desc[] = "<b>{$soft_info['name']} {$soft_info['version']}</b>: <i>{$soft_info['description']}</i>";
                        $data['soft'][$soft] = [
                            'name' => $soft_info['name'],
                            'version' => $soft_info['version'],
                            'description' => $soft_info['description']
                        ];
                    }
                    $data['html_desc'] = implode("<br>", $html_desc);
                }
                $oses[$system]['panel'][$panel]['softpack'][$softpack_name] = $data;
                $softpacks[$panel][$softpack_name] = $data;
            } else {
                $oses[$system]['panel'][$panel] = false;
            }
        }

        foreach ($oses as $system => $os) {
            $delete = true;
            foreach ($os['panel'] as $panel => $info) {
                if ($info !== false) {
                    $delete = false;
                }
            }
            if ($delete) {
                unset($vendors[$os['vendor']]['oses'][$system]);
            }
        }

        return compact('vendors', 'oses', 'softpacks');
    }
}
