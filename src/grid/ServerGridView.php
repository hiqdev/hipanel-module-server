<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\Url;
use hipanel\modules\server\widgets\DiscountFormatter;
use hipanel\modules\server\widgets\Expires;
use hipanel\modules\server\widgets\OSFormatter;
use hipanel\modules\server\widgets\State;
use hipanel\widgets\ArraySpoiler;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ServerGridView extends \hipanel\grid\BoxedGridView
{
    /**
     * @var array
     */
    public static $osImages;

    public static function setOsImages($osImages)
    {
        static::$osImages = $osImages;
    }

    public static function formatTariff($model)
    {
        if (Yii::$app->user->can('support')) {
            if ($model->parent_tariff && $model->parent_tariff !== $model->tariff) {
                return Html::tag('abbr', $model->parent_tariff, ['title' => $model->tariff, 'data-toggle' => 'tooltip']);
            } else {
                return $model->tariff;
            }
        } else {
            return !empty($model->parent_tariff) ? $model->parent_tariff : $model->tariff;
        }
    }

    public static function defaultColumns()
    {
        $osImages = self::$osImages;

        return [
            'server' => [
                'class' => MainColumn::class,
                'attribute' => 'name',
                'filterAttribute' => 'name_like',
                'note' => Yii::$app->user->can('support') ? 'label' : 'note',
                'noteOptions' => [
                    'url' => Yii::$app->user->can('support') ? Url::to('set-label') : Url::to('set-note'),
                ],
            ],
            'state' => [
                'class' => RefColumn::class,
                'i18nDictionary' => 'hipanel/server',
                'format' => 'raw',
                'gtype' => 'state,device',
                'value' => function ($model) {
                    $html = State::widget(compact('model'));
                    if ($model->status_time) {
                        $html .= ' '. Html::tag('nobr', Yii::t('hipanel/server', 'since {date}', ['date' => Yii::$app->formatter->asDate($model->status_time)]));
                    }
                    return $html;
                },
            ],
            'panel' => [
                'attribute' => 'panel',
                'format' => 'text',
                'contentOptions' => ['class' => 'text-uppercase'],
                'value' => function ($model) {
                    return $model->panel ? Yii::t('hipanel/server/panel', $model->panel) : Yii::t('hipanel/server/panel', 'No control panel');
                },
            ],
            'os' => [
                'attribute' => 'os',
                'format' => 'raw',
                'value' => function ($model) use ($osImages) {
                    return OSFormatter::widget([
                        'osimages' => $osImages,
                        'imageName' => $model->osimage,
                    ]);
                },
            ],
            'os_and_panel' => [
                'attribute' => 'os',
                'format' => 'raw',
                'value' => function ($model) use ($osImages) {
                    $html = OSFormatter::widget([
                        'osimages' => $osImages,
                        'imageName' => $model->osimage,
                    ]);
                    $html .= ' ' . $model->panel ?: '';
                    return $html;
                },
            ],
            'tariff_and_discount' => [
                'attribute' => 'tariff',
                'format' => 'raw',
                'value' => function ($model) {
                    return self::formatTariff($model) . ' ' . DiscountFormatter::widget([
                        'current' => $model->discounts['fee']['current'],
                        'next' => $model->discounts['fee']['next'],
                    ]);
                },
            ],
            'discount' => [
                'attribute' => 'discount',
                'label' => Yii::t('hipanel/server', 'Discount'),
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 1em'],
                'value' => function ($model) {
                    return DiscountFormatter::widget([
                        'current' => $model->discounts['fee']['current'],
                        'next' => $model->discounts['fee']['next'],
                    ]);
                },
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
            'expires' => [
                'filter' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 1em'],
                'value' => function ($model) {
                    return Expires::widget(compact('model'));
                },
            ],
            'tariff' => [
                'format' => 'raw',
                'attribute' => 'tariff',
                'value' => function ($model) {
                    return self::formatTariff($model);
                },
            ],
            'tariff_note' => [
                'attribute' => 'tariff_note',
                'value' => function ($model) {

                },
            ],
            'ips' => [
                'format' => 'raw',
                'attribute' => 'ips',
                'label' => Yii::t('hipanel/server', 'IP addresses'),
                'value' => function ($model) {
                    return ArraySpoiler::widget([
                        'data' => ArrayHelper::getColumn($model->ips, 'ip'),
                        'delimiter' => '<br />',
                        'visibleCount' => 3,
                        'button' => ['popoverOptions' => ['html' => true]],
                    ]);
                },
            ],
            'sale_time' => [
                'attribute' => 'sale_time',
                'format' => 'date',
            ],
            'note' => [
                'class' => XEditableColumn::class,
                'pluginOptions' => [
                    'url'       => Url::to('set-note'),
                ],
                'widgetOptions' => [
                    'linkOptions' => [
                        'data-type' => 'textarea',
                    ],
                ],
            ],
            'label' => [
                'class' => XEditableColumn::class,
                'visible' => Yii::$app->user->can('support'),
                'pluginOptions' => [
                    'url'       => Url::to('set-label'),
                ],
                'widgetOptions' => [
                    'linkOptions' => [
                        'data-type' => 'textarea',
                    ],
                ],
            ],
            'rack' => [
                'format' => 'html',
                'filterAttribute' => 'rack_like',
                'value'  => function ($model) {
                    return $model->switches['rack']['switch'];
                },
            ],
        ];
    }

    public static function defaultRepresentations()
    {
        return [
            'common' => [
                'label'   => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'server', 'client_id', 'seller_id',
                    'ips', 'state', 'expires',
                    'tariff_and_discount',
                ],
            ],
            'manager' => Yii::$app->user->can('support') ? [
                'label'   => Yii::t('hipanel/server', 'manager'),
                'columns' => [
                    'checkbox', 'client_id',
                    'rack', 'dc', 'server', 'tariff', 'hwsummary',
                ],
            ] : null,
        ];
    }
}
