<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\grid;

use hipanel\helpers\Url;
use hipanel\widgets\ArraySpoiler;
use Yii;
use hipanel\grid\ActionColumn;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\modules\server\widgets\DiscountFormatter;
use hipanel\modules\server\widgets\Expires;
use hipanel\modules\server\widgets\OSFormatter;
use hipanel\modules\server\widgets\State;
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

    public static function formatTariff($model) {
        if (Yii::$app->user->can('support')) {
            if ($model->parent_tariff && $model->parent_tariff !== $model->tariff) {
                return Html::tag('abbr', $model->parent_tariff, ['title' => $model->tariff, 'data-toggle' => "tooltip"]);
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
                'class' => MainColumn::className(),
                'attribute' => 'name',
                'filterAttribute' => 'name_like',
                'note' => Yii::$app->user->can('support') ? 'label' : 'note',
                'noteOptions' => [
                    'url' => Yii::$app->user->can('support') ? Url::to('set-label') : Url::to('set-note'),
                ]
            ],
            'state' => [
                'class' => RefColumn::className(),
                'format' => 'raw',
                'gtype' => 'state,device',
                'value' => function ($model) {
                    $html = State::widget(compact('model'));
                    if ($model->status_time) {
                        $html .= ' ' . Yii::t('app', 'since') . ' ' . Yii::$app->formatter->asDate($model->status_time);
                    }
                    return $html;
                },
            ],
            'panel' => [
                'attribute' => 'panel',
                'format' => 'text',
                'contentOptions' => ['class' => 'text-uppercase'],
                'value' => function ($model) {
                    return $model->panel ?: Yii::t('app', 'No control panel');
                }
            ],
            'os' => [
                'attribute' => 'os',
                'format' => 'raw',
                'value' => function ($model) use ($osImages) {
                    return OSFormatter::widget([
                        'osimages' => $osImages,
                        'imageName' => $model->osimage
                    ]);
                }
            ],
            'os_and_panel' => [
                'attribute' => 'os',
                'format' => 'raw',
                'value' => function ($model) use ($osImages) {
                    $html = OSFormatter::widget([
                        'osimages' => $osImages,
                        'imageName' => $model->osimage
                    ]);
                    $html .= ' ' . $model->panel ?: '';
                    return $html;
                }
            ],
            'tariff_and_discount' => [
                'attribute' => 'tariff',
                'format' => 'raw',
                'value' => function ($model) {
                    return self::formatTariff($model) . ' ' . DiscountFormatter::widget([
                        'current' => $model->discounts['fee']['current'],
                        'next' => $model->discounts['fee']['next'],
                    ]);
                }
            ],
            'discount' => [
                'attribute' => 'discount',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 1em'],
                'value' => function ($model) {
                    return DiscountFormatter::widget([
                        'current' => $model->discounts['fee']['current'],
                        'next' => $model->discounts['fee']['next'],
                    ]);
                }
            ],
            'actions' => [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'header' => Yii::t('app', 'Actions'),
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
                'value'=> function ($model) {
                    return self::formatTariff($model);
                }
            ],
            'tariff_note' => [
                'attribute' => 'tariff_note',
                'value' => function ($model) {

                }
            ],
            'ips' => [
                'format' => 'raw',
                'attribute' => 'ips',
                'value' => function ($model) {
                    return ArraySpoiler::widget([
                        'data' => $model->ips,
                        'delimiter' => '<br />',
                        'visibleCount' => 3,
                        'button' => ['popoverOptions' => ['html' => true]]
                    ]);
                }
            ],
            'sale_time' => [
                'attribute' => 'sale_time',
                'format' => 'date',
            ],
            'note' => [
                'class' => 'hiqdev\xeditable\grid\XEditableColumn',
                'pluginOptions' => [
                    'emptytext' => Yii::t('app', 'set note'),
                    'url'       => Url::to('set-note')
                ]
            ],
            'label' => [
                'class' => 'hiqdev\xeditable\grid\XEditableColumn',
                'visible' => Yii::$app->user->can('support'),
                'pluginOptions' => [
                    'emptytext' => Yii::t('app', 'set internal note'),
                    'url'       => Url::to('set-label')
                ],
            ]
        ];
    }
}
