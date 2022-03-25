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

use hipanel\actions\Action;
use hipanel\actions\ComboSearchAction;
use hipanel\actions\IndexAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\ProxyAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderAction;
use hipanel\actions\RenderJsonAction;
use hipanel\actions\RequestStateAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\VariantsAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\models\Ref;
use hipanel\modules\finance\models\Tariff;
use hipanel\modules\server\actions\BulkPowerManagementAction;
use hipanel\modules\server\actions\BulkSetRackNo;
use hipanel\modules\server\cart\ServerRenewProduct;
use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\forms\ServerForm;
use hipanel\modules\server\helpers\ServerHelper;
use hipanel\modules\server\models\HardwareSettings;
use hipanel\modules\server\models\MailSettings;
use hipanel\modules\server\models\MonitoringSettings;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\models\query\ServerQuery;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\models\ServerSearch;
use hipanel\modules\server\models\ServerUseSearch;
use hipanel\modules\server\models\SoftwareSettings;
use hipanel\modules\server\widgets\ResourceConsumption;
use hiqdev\hiart\Collection;
use hiqdev\hiart\ResponseErrorException;
use hiqdev\yii2\cart\actions\AddToCartAction;
use Yii;
use yii\base\Event;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ServerController extends CrudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'server-actions-verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'reboot' => ['post'],
                    'reset' => ['post'],
                    'shutdown' => ['post'],
                    'power-off' => ['post'],
                    'power-on' => ['post'],
                    'reset-password' => ['post'],
                    'enable-block' => ['post'],
                    'disable-block' => ['post'],
                    'refuse' => ['post'],
                    'flush-switch-graphs' => ['post'],
                ],
            ],
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'monitoring-settings' => 'support',
                    'software-settings' => 'support',
                    'hardware-settings' => 'support',
                    'create' => 'server.create',
                    'update' => 'server.update',
                    'delete' => 'server.delete',
                    'renew' => 'server.pay',
                    'refuse' => 'server.pay',
                    'assign-hubs' => 'server.update',
                    'set-units' => 'server.update',
                    'set-rack-no' => 'server.update',
                    'reboot' => 'server.control-power',
                    'shutdown' => 'server.control-power',
                    'power-on' => 'server.control-power',
                    'power-off' => 'server.control-power',
                    'reset' => 'server.control-power',
                    'reinstall' => 'server.control-system',
                    'boot-live' => 'server.control-system',
                    'enable-wizzard' => 'server.update',
                    'disable-wizzard' => 'server.wizzard',
                    'enable-vnc' => 'server.control-system',
                    'enable-block' => 'server.enable-block',
                    'disable-block' => 'server.disable-block',
                    'set-label' => 'server.set-label',
                    'set-note' => 'server.set-note',
                    'clear-resources' => 'consumption.delete',
                    'flush-switch-graphs' => 'consumption.delete',
                    '*' => 'server.read',
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
                'responseVariants' => [
                    'get-total-count' => fn(VariantsAction $action): int => Server::find()->count(),
                ],
                'findOptions' => ['with_requests' => true, 'with_discounts' => true],
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $query = $action->getDataProvider()->query;

                    $query->withBindings();

                    if (Yii::$app->user->can('sale.read')) {
                        $query->withSales();
                    }
                    if (Yii::getAlias('@ip', false)) {
                        $query->withIps();
                    }
                    if ($this->indexPageUiOptionsModel->representation === 'billing' && Yii::$app->user->can('consumption.read')) {
                        $query->withConsumptions()->withHardwareSales();
                    }

                    $query
                        ->andWhere(['with_requests' => 1])
                        ->andWhere(['with_discounts' => 1])
                        ->select(['*']);
                },
                'filterStorageMap' => [
                    'name_dc'    => 'server.server.name_dc',
                    'name_like'  => 'server.server.name',
                    'label_like' => 'server.server.label',
                    'note_like'  => 'server.server.note',
                    'order_no'   => 'server.server.order_no',
                    'dc_like'    => 'server.server.dc',
                    'ip_like'    => 'server.server.ip',

                    'ips'       => 'hosting.ip.ip_in',
                    'client_id' => 'client.client.id',
                    'seller_id' => 'client.client.seller_id',

                    'hwsummary_like' => 'server.server.hwsummary',
                    'type'           => 'server.server.type',
                    'state'          => 'server.server.state',
                    'net_like'       => 'server.server.net',
                    'kvm_like'       => 'server.server.kvm',
                    'pdu_like'       => 'server.server.pdu',
                    'rack_like'      => 'server.server.rack',
                    'mac_like'       => 'server.server.mac',

                    'tariff_like'  => 'server.server.tariff',
                    'wizzarded_eq' => 'server.server.wizzarded',

                    'hide_nic'  => 'server.server.hide_hic',
                    'hide_vds' => 'server.server.hide_vds',
                ],
            ],
            'search' => [
                'class' => ComboSearchAction::class,
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new ServerForm(['scenario' => 'create']),
                    'scenario' => 'create',
                ],
                'success' => Yii::t('hipanel:server', 'Server has been created'),
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new ServerForm(),
                    'scenario' => 'update',
                ],
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    if (Yii::getAlias('@ip', false)) {
                        $dataProvider->query->joinWith('ips');
                    }
                },
                'data' => function (Action $action, array $data) {
                    $result = [];
                    foreach ($data['models'] as $model) {
                        $result['models'][] = ServerForm::fromServer($model);
                    }
                    $result['model'] = reset($result['models']);

                    return $result;
                },
                'success' => Yii::t('hipanel:server', 'Server has been updated'),
            ],
            'assign-hubs' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Hubs have been assigned'),
                'view' => 'assign-hubs',
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->withBindings()->select(['*']);
                },
                'collection' => [
                    'class' => Collection::class,
                    'model' => new AssignHubsForm(),
                    'scenario' => 'default',
                ],
                'data' => function (Action $action, array $data) {
                    $result = [];
                    foreach ($data['models'] as $model) {
                        $result['models'][] = AssignHubsForm::fromOriginalModel($model);
                    }
                    if (!$result['models']) {
                        throw new NotFoundHttpException('There are no entries available for the selected operation. The type of selected records may not be suitable for the selected operation.');
                    }
                    $result['model'] = reset($result['models']);

                    return $result;
                },
            ],
            'set-units' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Units property was changed'),
                'view' => 'setUnits',
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $servers = Yii::$app->request->post('HardwareSettings');
                    $units = ArrayHelper::remove($servers, 'units');
                    foreach ($servers as $id => $server) {
                        $servers[$id]['units'] = $units;
                    }
                    $action->collection->load($servers);
                },
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    /** @var ServerQuery $query */
                    $query = $action->getDataProvider()->query;
                    $query->withHardwareSettings();
                },
                'on beforeLoad' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;

                    $action->collection->setModel(new HardwareSettings(['scenario' => 'set-units']));
                },
            ],
            'set-rack-no' => [
                'class' => BulkSetRackNo::class,
                'success' => Yii::t('hipanel:server', 'Rack No. has been assigned'),
                'view' => 'setRackNo',
                'collection' => [
                    'class' => Collection::class,
                    'model' => new AssignHubsForm(),
                    'scenario' => 'default',
                ],
            ],
            'hardware-settings' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Hardware properties was changed'),
                'view' => 'modal/hardwareSettings',
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    /** @var ServerQuery $query */
                    $query = $action->getDataProvider()->query;
                    $query->withHardwareSettings();
                },
                'on beforeLoad' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;

                    $action->collection->setModel(HardwareSettings::class);
                },
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function () {
                            $server = Yii::$app->request->post('HardwareSettings');

                            return ['@server/view', 'id' => $server['id']];
                        },
                    ],
                ],
            ],
            'software-settings' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Software properties was changed'),
                'view' => 'modal/softwareSettings',
                'scenario' => 'default',
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    /** @var ServerQuery $query */
                    $query = $action->getDataProvider()->query;
                    $query->withSoftwareSettings();
                },
                'on beforeLoad' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;

                    $action->collection->setModel(SoftwareSettings::class);
                },
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function () {
                            $server = Yii::$app->request->post('SoftwareSettings');

                            return ['@server/view', 'id' => $server['id']];
                        },
                    ],
                ],
            ],
            'monitoring-settings' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Monitoring properties was changed'),
                'view' => 'modal/monitoringSettings',
                'scenario' => 'default',
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $query = $action->getDataProvider()->query;
                    $query->withMonitoringSettings()->select(['*']);
                },
                'on beforeLoad' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;

                    $action->collection->setModel(MonitoringSettings::class);
                },
                'data' => function ($action) {
                    return [
                        'nicMediaOptions' => $action->controller->getFullFromRef('type,nic_media'),
                    ];
                },
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function () {
                            $server = Yii::$app->request->post('MonitoringSettings');

                            return ['@server/view', 'id' => $server['id']];
                        },
                    ],
                ],
            ],
            'mail-settings' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server', 'Mail settings have been changed successfully'),
                'view' => 'modal/mailSettings',
                'scenario' => 'default',
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $query = $action->getDataProvider()->query;
                    $query->withMailSettings()->select(['*']);
                },
                'on beforeLoad' => function (Event $event) {
                    /** @var Action $action */
                    $action = $event->sender;

                    $action->collection->setModel(MailSettings::class);
                },
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => function () {
                            $server = Yii::$app->request->post('MailSettings');

                            return ['@server/view', 'id' => $server['id']];
                        },
                    ],
                ],
            ],
            'view' => [
                'class' => ViewAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    /** @var ServerQuery $query */
                    $query = $action->getDataProvider()->query;
                    $query
                        ->withSoftwareSettings()
                        ->withHardwareSettings()
                        ->withParts()
                        ->withBindings()
                        ->withBlocking()
                        ->withUses()
                        ->withConsumptions()
                        ->withSales()
                        ->joinWith(['switches']);

                    if (Yii::getAlias('@ip', false)) {
                        $query
                            ->joinWith(['ips'])
                            ->andWhere(['with_ips' => 1]);
                    }

                    // TODO: ipModule is not wise yet. Redo
                    $query
                        ->andWhere(['with_requests' => 1])
                        ->andWhere(['show_deleted' => 1])
                        ->andWhere(['with_discounts' => 1])
                        ->select(['*']);
                },
                'data' => function ($action) {
                    /**
                     * @var Action
                     * @var self $controller
                     * @var Server $model
                     */
                    $controller = $action->controller;
                    $model = $action->getModel();
                    $model->vnc = $controller->getVNCInfo($model);

                    $panels = $controller->getPanelTypes();

                    $cacheKeys = [__METHOD__, 'view', 'tariff', $model->tariff_id, Yii::$app->user->getId()];
                    $tariff = Yii::$app->cache->getOrSet($cacheKeys, function () use ($model) {
                        return Tariff::find()->where([
                            'id' => $model->tariff_id,
                            'show_final' => true,
                            'show_deleted' => true,
                            'with_resources' => true,
                        ])->joinWith('resources')->one();
                    });

                    $ispSupported = false;
                    if ($tariff !== null) {
                        foreach ($tariff->getResources() as $resource) {
                            if (!empty($resource) && !empty($resource->type) && $resource->type === 'isp' && $resource->quantity > 0) {
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

                    return [
                        'model' => $model,
                        'osimages' => $osimages,
                        'osimageslivecd' => $osimageslivecd ?? [],
                        'groupedOsimages' => $groupedOsimages,
                        'panels' => $panels,
                        'blockReasons' => $blockReasons,
                    ];
                },
            ],
            'resources' => [
                'class' => ViewAction::class,
                'view' => 'resources',
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->withUses()->select(['*']);
                },
                'data' => function ($action) {
                    $model = $action->getModel();
                    list($chartsLabels, $chartsData) = $model->groupUsesForCharts();

                    return compact('model', 'chartsData', 'chartsLabels');
                },
            ],
            'requests-state' => [
                'class' => RequestStateAction::class,
                'model' => Server::class,
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'view' => 'modal/_bulkSetNote',
                'success' => Yii::t('hipanel:server', 'Note changed'),
                'error' => Yii::t('hipanel:server', 'Failed to change note'),
            ],
            'set-label' => [
                'class' => SmartUpdateAction::class,
                'view' => 'modal/_bulkSetLabel',
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
            'bulk-sale' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'sale',
                'view' => 'modal/_bulkSale',
                'success' => Yii::t('hipanel:server', 'Servers were sold'),
                'POST pjax' => [
                    'save' => true,
                    'success' => [
                        'class' => ProxyAction::class,
                        'action' => 'index',
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $request = Yii::$app->request;

                    if ($request->isPost) {
                        $values = [];
                        foreach (['client_id', 'tariff_id', 'sale_time', 'move_accounts'] as $attribute) {
                            $value = $request->post($attribute);
                            if (!empty($value)) {
                                $values[$attribute] = $value;
                            }
                        }
                        foreach ($action->collection->models as $model) {
                            foreach ($values as $attr => $value) {
                                $model->setAttribute($attr, $value);
                            }
                        }
                    }
                },
            ],
            'set-one-type' => [
                'class' => SmartUpdateAction::class,
                'view' => 'setOneType',
                'success' => Yii::t('hipanel:server', 'Type was changed'),
                'error' => Yii::t('hipanel:server', 'Failed to change type'),
                'scenario' => 'set-type',
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $servers = Yii::$app->request->post('Server');
                    $type = ArrayHelper::remove($servers, 'type');
                    foreach ($servers as $id => $server) {
                        $servers[$id]['type'] = $type;
                    }
                    $action->collection->setModel($this->newModel(['scenario' => 'set-type']));
                    $action->collection->load($servers);
                },
            ],
            'set-type' => [
                'class' => SmartUpdateAction::class,
                'view' => '_bulkSetType',
                'success' => Yii::t('hipanel:server', 'Type was changed'),
                'error' => Yii::t('hipanel:server', 'Failed to change type'),
            ],
            'reboot' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server', 'Reboot task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the rebooting'),
            ],
            'reset' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server', 'Reset task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the resetting'),
            ],
            'shutdown' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server', 'Shutdown task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the shutting down'),
            ],
            'power-off' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server', 'Power off task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning power off'),
            ],
            'power-on' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server', 'Power on task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning power on'),
            ],
            'reset-password' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server', 'Root password reset task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the resetting root password'),
            ],
            'enable-block' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server', 'Server was blocked successfully'),
                'error' => Yii::t('hipanel:server', 'Error during the server blocking'),
                'POST html' => [
                    'save' => true,
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
                'view' => 'modal/_bulkEnableBlock',
                'data' => function ($action, $data) {
                    return array_merge($data, [
                        'blockReasons' => $this->getBlockReasons(),
                    ]);
                },
            ],
            'disable-block' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server', 'Server was unblocked successfully'),
                'error' => Yii::t('hipanel:server', 'Error during the server unblocking'),
                'POST html' => [
                    'save' => true,
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
                                'comment' => $comment,
                                'type' => $type,
                            ]);
                        }
                    }
                },
            ],
            'bulk-disable-block-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => 'modal/_bulkDisableBlock',
                'data' => function ($action, $data) {
                    return array_merge($data, [
                        'blockReasons' => $this->getBlockReasons(),
                    ]);
                },
            ],
            'refuse' => [
                'class' => SmartPerformAction::class,
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
                'class' => SmartPerformAction::class,
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
            'validate-hw-form' => [
                'class' => ValidateFormAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new HardwareSettings(),
                ],
            ],
            'validate-crud-form' => [
                'class' => ValidateFormAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new ServerForm(),
                ],
            ],
            'validate-assign-hubs-form' => [
                'class' => ValidateFormAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new AssignHubsForm(),
                ],
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'buy' => [
                'class' => RedirectAction::class,
                'url' => Yii::$app->params['organization.url'],
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
                'view' => 'modal/_bulkDelete',
            ],
            'clear-resources' => [
                'class' => SmartPerformAction::class,
                'view' => '_clearResources',
                'success' => Yii::t('hipanel:server', 'Servers resources were cleared successfully'),
                'error' => Yii::t('hipanel:server', 'Error occurred during server resources flushing'),
            ],
            'clear-resources-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => '_clearResources',
            ],
            'flush-switch-graphs' => [
                'class' => SmartPerformAction::class,
                'view' => '_clearResources',
                'success' => Yii::t('hipanel:server', 'Switch graphs were flushed successfully'),
                'error' => Yii::t('hipanel:server', 'Error occurred during switch graphs flushing'),
            ],
            'flush-switch-graphs-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => '_flushSwitchGraphs',
            ],

            // Bulk power management actions
            'bulk-power-on' => [
                'class' => BulkPowerManagementAction::class,
                'view' => 'modal/bulkPowerManagement',
                'success' => Yii::t('hipanel:server', 'Power on task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning power on'),
            ],
            'bulk-power-off' => [
                'class' => BulkPowerManagementAction::class,
                'view' => 'modal/bulkPowerManagement',
                'success' => Yii::t('hipanel:server', 'Power off task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning power off'),
            ],
            'bulk-reboot' => [
                'class' => BulkPowerManagementAction::class,
                'view' => 'modal/bulkPowerManagement',
                'success' => Yii::t('hipanel:server', 'Reboot task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning reboot'),
            ],
            'bulk-boot-to-bios' => [
                'class' => BulkPowerManagementAction::class,
                'view' => 'modal/bulkPowerManagement',
                'success' => Yii::t('hipanel:server', 'Boot to BIOS task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning boot to BIOS'),
            ],
            'bulk-boot-via-network' => [
                'class' => BulkPowerManagementAction::class,
                'view' => 'modal/bulkPowerManagement',
                'success' => Yii::t('hipanel:server', 'Boot via network task has been successfully added to queue'),
                'error' => Yii::t('hipanel:server', 'Error during the turning boot via network'),
            ],
        ]);
    }

    /**
     * Gets info of VNC on the server.
     *
     * @param Server $model
     * @param bool $enable
     * @throws ResponseErrorException
     * @return array
     */
    public function getVNCInfo($model, $enable = false)
    {
        if ($enable) {
            try {
                $vnc = Server::perform('enable-VNC', ['id' => $model->id]);
                $vnc['endTime'] = time() + 28800;
                Yii::$app->cache->set([__METHOD__, $model->id, $model], $vnc, 28800);
                $vnc['enabled'] = true;
            } catch (ResponseErrorException $e) {
                if ($e->getMessage() !== 'vds_has_tasks') {
                    throw $e;
                }
            }
        } else {
            if (!empty($model->statuses['serverEnableVNC']) && strtotime('+8 hours', strtotime($model->statuses['serverEnableVNC'])) > time()) {
                $vnc = Yii::$app->cache->getOrSet([__METHOD__, $model->id, $model], function () use ($model) {
                    return ArrayHelper::merge([
                        'endTime' => strtotime($model->statuses['serverEnableVNC']) + 28800,
                    ], Server::perform('enable-VNC', ['id' => $model->id]));
                }, 28800);
            }
            $vnc['enabled'] = !empty($model->statuses['serverEnableVNC']) && strtotime('+8 hours', strtotime($model->statuses['serverEnableVNC'])) > time();
        }

        return $vnc;
    }

    public function actionDrawChart()
    {
        $post = Yii::$app->request->post();
        $types = array_merge(['server_traf', 'server_traf95'], array_keys(ResourceConsumption::types()));
        if (!in_array($post['type'], $types, true)) {
            throw new NotFoundHttpException();
        }

        $searchModel = new ServerUseSearch();
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;
        $dataProvider->query->action('get-uses');
        $dataProvider->query->andWhere($post);
        $models = $dataProvider->getModels();

        [$labels, $data] = ServerHelper::groupUsesForChart($models);

        return $this->renderAjax('_consumption', [
            'labels' => $labels,
            'data' => $data,
            'consumptionBase' => $post['type'],
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
        $models = Yii::$app->cache->getOrSet([__METHOD__], function () {
            return Osimage::findAll(['livecd' => true]);
        }, 3600);

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

    protected function getFullFromRef($gtype)
    {
        $callingMethod = debug_backtrace()[1]['function'];
        $result = Yii::$app->get('cache')->getOrSet([$callingMethod], function () use ($gtype) {
            $result = ArrayHelper::map(Ref::find()->where([
                'gtype' => $gtype,
                'select' => 'full',
            ])->all(), 'id', function ($model) {
                return Yii::t('hipanel:server:hub', $model->label);
            });

            return $result;
        }, 86400 * 24); // 24 days

        return $result;
    }
}
