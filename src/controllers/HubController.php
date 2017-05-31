<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\helpers\ArrayHelper;
use hipanel\models\Ref;
use Yii;
use yii\base\Event;

class HubController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'data' => function () {
                    return [
                        'types' => $this->getTypes(),
                    ];
                }
            ],
            'view' => [
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->joinWith(['bindings']);
                    $dataProvider->query->andWhere(['with_bindings' => 1]);
                },
                'class' => ViewAction::class,
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel:server:hub', 'Switch was created'),
                'data' => function () {
                    return [
                        'types' => $this->getTypes(),
                    ];
                }
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:server:hub', 'Switch was updated'),
                'data' => function () {
                    return [
                        'types' => $this->getTypes(),
                    ];
                }
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
                }
            ],
        ];
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
            $result = ArrayHelper::map(Ref::find()->where(['gtype' => $gtype, 'select' => 'full'])->all(), 'id', function ($model) {
                return Yii::t('hipanel:server:hub', $model->label);
            });

            return $result;
        }, 86400 * 24); // 24 days

        return $result;
    }
}