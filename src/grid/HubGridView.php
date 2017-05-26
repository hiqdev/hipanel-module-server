<?php

namespace hipanel\modules\server\grid;

use hipanel\grid\RefColumn;
use hipanel\modules\client\grid\ClientColumn;
use hipanel\modules\server\menus\HubActionsMenu;
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\helpers\Html;

class HubGridView extends \hipanel\grid\BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'model' => [
                'contentOptions' => [
                    'class' => 'text-right'
                ]
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => HubActionsMenu::class,
            ],
            'buyer' => [
                'class' => ClientColumn::class,
                'idAttribute' => 'buyer_id',
                'attribute' => 'buyer_id',
                'nameAttribute' => 'buyer',
                'enableSorting' => false,

            ],
            'switch' => [
                'format' => 'html',
                'label' => Yii::t('hipanel:server', 'Switch'),
                'value' => function ($model) {
                    $name = Html::tag('span', $model->name, ['class' => 'text-bold text-info']);
                    $note = Html::tag('small', $model->note, ['class' => 'text-muted']);
                    return sprintf('%s %s %s', $name, '', $note);
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
