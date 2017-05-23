<?php

namespace hipanel\modules\server\grid;

use hipanel\grid\RefColumn;
use Yii;
use yii\helpers\Html;

class HubGridView extends \hipanel\grid\BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'inn' => [

            ],
            'buyer' => [

            ],
            'model' => [

            ],
            'switch' => [
                'value' => function ($model) {
                    return Html::encode($model->name);
                }
            ],
            'type' => [
                'class' => RefColumn::class,
                'attribute' => 'type_id',
                'i18nDictionary' => 'hipanel:server:hub',
                'gtype' => 'type,device,switch',
                'value' => function ($model) {
                    return Yii::t('hipanel:server:hub', $model->type_label);
                },
            ],
        ];
    }
}
