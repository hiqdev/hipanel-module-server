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
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\helpers\ArrayHelper;
use hipanel\models\Ref;
use hipanel\modules\server\forms\AssignSwitchesForm;
use hipanel\modules\server\forms\HubSellForm;
use hiqdev\hiart\Collection;
use Yii;
use yii\base\Event;
use yii\web\NotFoundHttpException;

class HubController extends CrudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'hub.create',
                    'update,options' => 'hub.update',
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
                'data' => function () {
                    return [
                        'types' => $this->getTypes(),
                    ];
                },
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
                        ->andWhere([
                            'with_bindings' => 1,
                            'with_servers' => 1,
                            'with_hardwareSettings' => 1,
                        ]);
                },
                'class' => ViewAction::class,
                'data' => function () {
                    return [
                        'snmpOptions' => $this->getSnmpOptions(),
                        'digitalCapacityOptions' => $this->getDigitalCapacityOptions(),
                        'nicMediaOptions' => $this->getNicMediaOptions(),
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
            ])->all(), 'id', function ($model) {
                return Yii::t('hipanel:server:hub', $model->label);
            });

            return $result;
        }, 86400 * 24); // 24 days

        return $result;
    }
}
