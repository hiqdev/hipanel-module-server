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
use hipanel\actions\IndexAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\base\Module;
use hipanel\filters\EasyAccessControl;
use hipanel\helpers\ArrayHelper;
use hipanel\models\Ref;
use hipanel\modules\finance\providers\ConsumptionsProvider;
use hipanel\modules\server\actions\BulkSetRackNo;
use hipanel\modules\server\models\HardwareSettings;
use hipanel\modules\server\models\MonitoringSettings;
use hipanel\modules\server\forms\AssignSwitchesForm;
use hipanel\modules\server\forms\HubSellForm;
use hiqdev\hiart\Collection;
use Yii;
use yii\base\Event;
use yii\web\NotFoundHttpException;

class HubController extends CrudController
{
    public function __construct(
        string $id,
        Module $module,
        readonly private ConsumptionsProvider $consumptionsProvider,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'hub.create',
                    'update,options' => 'hub.update',
                    'monitoring-settings' => 'server.manage-settings',
                    'set-rack-no' => 'hub.update',
                    'set-note' => 'hub.update',
                    '*' => 'hub.read',
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $query = $action->getDataProvider()->query;
                    if ($this->indexPageUiOptionsModel->representation === 'consumption' && Yii::$app->user->can('consumption.read')) {
                        $query->withResources();
                    }
                },
                'data' => function () {
                    return [
                        'types' => $this->getTypes(),
                    ];
                },
            ],
            'restore-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => 'modal/_restore',
                'scenario' => 'restore',
                'on beforePerform' => function (Event $event) {
                    $event->sender->getDataProvider()->query->withDeleted();
                },
            ],
            'restore' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:server:hub', 'The restore has been successful'),
                'error' => Yii::t('hipanel:server:hub', 'An error has occurred while the restoring hub(s)'),
            ],
            'view' => [
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->joinWith([
                        'bindings',
                        'hardwareSettings',
                    ]);
                    $dataProvider->query
                        ->withDeleted()
                        ->andWhere([
                            'with_bindings' => 1,
                            'with_servers' => 1,
                            'with_hardwareSettings' => 1,
                        ]);
                },
                'class' => ViewAction::class,
                'data' => function (ViewAction $action) {
                    $model = $action->getModel();
                    $consumption = $this->consumptionsProvider->findById($model->id);

                    return [
                        'snmpOptions' => $this->getSnmpOptions(),
                        'digitalCapacityOptions' => $this->getDigitalCapacityOptions(),
                        'nicMediaOptions' => $this->getNicMediaOptions(),
                        'consumption' => $consumption,
                    ];
                },
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel:server:hub', 'Switch was created'),
                'data' => function () {
                    return [
                        'types' => $this->getTypes(),
                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server:hub', 'Switch was updated'),
                'data' => function () {
                    return [
                        'types' => $this->getTypes(),
                    ];
                },
            ],
            'options' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server:hub', 'Options was updated'),
                'data' => function () {
                    return [
                        'snmpOptions' => $this->getSnmpOptions(),
                        'digitalCapacityOptions' => $this->getDigitalCapacityOptions(),
                        'nicMediaOptions' => $this->getNicMediaOptions(),
                    ];
                },
            ],
            'sell' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server:hub', 'Switches were sold'),
                'view' => 'modal/_bulkSale',
                'collection' => [
                    'class' => Collection::class,
                    'model' => new HubSellForm(),
                    'scenario' => 'sell',
                ],
                'data' => function (Action $action, array $data) {
                    $result = [];
                    foreach ($data['models'] as $model) {
                        $result['models'][] = HubSellForm::fromHub($model);
                    }
                    $result['model'] = reset($result['models']);

                    return $result;
                },
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $request = Yii::$app->request;

                    if ($request->isPost) {
                        $values = [];
                        foreach (['client_id', 'tariff_id', 'sale_time'] as $attribute) {
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
            'assign-switches' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server:hub', 'Switches have been edited'),
                'view' => 'assign-switches',
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->withBindings()->select(['*']);
                },
                'collection' => [
                    'class' => Collection::class,
                    'model' => new AssignSwitchesForm(),
                    'scenario' => 'default',
                ],
                'data' => function (Action $action, array $data) {
                    $result = [];
                    foreach ($data['models'] as $model) {
                        $result['models'][] = AssignSwitchesForm::fromOriginalModel($model);
                    }
                    if (!$result['models']) {
                        throw new NotFoundHttpException('There are no entries available for the selected operation.');
                    }
                    $result['model'] = reset($result['models']);

                    return $result;
                },
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel:server:hub', 'Switches have been deleted'),
            ],
            'set-rack-no' => [
                'class' => BulkSetRackNo::class,
                'success' => Yii::t('hipanel:server', 'Rack No. has been assigned'),
                'view' => 'setRackNo',
                'collection' => [
                    'class' => Collection::class,
                    'model' => new AssignSwitchesForm(),
                    'scenario' => 'default',
                ],
            ],
            'validate-switches-form' => [
                'class' => ValidateFormAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new AssignSwitchesForm(),
                ],
            ],
            'validate-sell-form' => [
                'class' => ValidateFormAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new HubSellForm(),
                ],
            ],
            'validate-hw-form' => [
                'class' => ValidateFormAction::class,
                'collection' => [
                    'class' => Collection::class,
                    'model' => new HardwareSettings(),
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
                            $hub = Yii::$app->request->post('MonitoringSettings');

                            return ['@hub/view', 'id' => $hub['id']];
                        },
                    ],
                ],
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'view' => 'modal/_bulkSetNote',
                'success' => Yii::t('hipanel:server', 'Note changed'),
                'error' => Yii::t('hipanel:server', 'Failed to change note'),
            ],
        ]);
    }

    protected function getTypes()
    {
        return $this->getFullFromRef('type,device,switch');
    }

    protected function getSnmpOptions()
    {
        return $this->getFullFromRef('type,snmp_version');
    }

    protected function getDigitalCapacityOptions()
    {
        return $this->getFullFromRef('type,digit_capacity');
    }

    protected function getNicMediaOptions()
    {
        return $this->getFullFromRef('type,nic_media');
    }

    protected function getFullFromRef($gtype)
    {
        $callingMethod = debug_backtrace()[1]['function'];
        $result = Yii::$app->get('cache')->getOrSet([$callingMethod], function () use ($gtype) {
            $result = ArrayHelper::map(Ref::find()->where([
                'gtype' => $gtype,
                'select' => 'full',
            ])->all(),
                'id',
                function ($model) {
                    return Yii::t('hipanel:server:hub', $model->label);
                });

            return $result;
        }, 86400 * 24); // 24 days

        return $result;
    }
}
