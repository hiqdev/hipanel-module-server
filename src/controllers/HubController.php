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
            ],
        ];
    }

    protected function getTypes()
    {
        $companies = Yii::$app->get('cache')->getOrSet([__METHOD__], function () {
            $result = ArrayHelper::map(Ref::find()->where(['gtype' => 'type,device,switch', 'select' => 'full'])->all(), 'id', function ($model) {
                return Yii::t('hipanel:server:hub', $model->label);
            });

            return $result;
        }, 86400 * 24); // 24 days

        return $companies;
    }
}