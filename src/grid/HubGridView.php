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
            'inn' => [
                'enableSorting' => false,
            ],
            'model' => [
                'enableSorting' => false,
            ],
            'ip' => [
                'enableSorting' => false,
            ],
            'mac' => [
                'enableSorting' => false,
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => HubActionsMenu::class,
            ],
            'traf_server_id' => [
                'format' => 'html',
                'enableSorting' => false,
                'value' => function ($model) {
                    return Html::a($model->traf_server_id_label, ['@server/view', 'id' => $model->traf_server_id]);
                }
            ],
            'vlan_server_id' => [
                'format' => 'html',
                'enableSorting' => false,
                'value' => function ($model) {
                    return Html::a($model->vlan_server_id_label, ['@server/view', 'id' => $model->vlan_server_id]);
                }
            ],
            'buyer' => [
                'label' => Yii::t('hipanel:server:hub', 'Buyer'),
                'class' => ClientColumn::class,
                'idAttribute' => 'buyer_id',
                'attribute' => 'buyer_id',
                'nameAttribute' => 'buyer',
                'enableSorting' => false,

            ],
            'switch' => [
                'format' => 'html',
                'enableSorting' => false,
                'label' => Yii::t('hipanel:server', 'Switch'),
                'value' => function ($model) {
                    $name = Html::tag('span', $model->name, ['class' => 'text-bold text-info']);
                    $note = Html::tag('small', $model->note, ['class' => 'text-muted']);
                    return sprintf('%s %s %s', $name, '', $note);
                }
            ],
            'type' => [
                'class' => RefColumn::class,
                'enableSorting' => false,
                'findOptions' => [
                    'select' => 'full',
                    'mapOptions' => ['from' => 'id', 'to' => 'label'],
                ],
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
