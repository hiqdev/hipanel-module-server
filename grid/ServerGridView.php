<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\modules\server\widgets\DiscountFormatter;
use hipanel\modules\server\widgets\Expires;
use hipanel\modules\server\widgets\OSFormatter;
use hipanel\modules\server\widgets\State;
use Yii;
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

    public static function defaultColumns()
    {
        $osImages = self::$osImages;

        return [
            'server'    => [
                'class'           => MainColumn::className(),
                'attribute'       => 'name',
                'filterAttribute' => 'name_like',
                'note'            => true
            ],
            'state'     => [
                'class'  => RefColumn::className(),
                'format' => 'raw',
                'gtype'  => 'state,device',
                'value'  => function ($model) {
                    return State::widget(compact('model'));
                }
            ],
            'panel'     => [
                'attribute'      => 'panel',
                'format'         => 'text',
                'contentOptions' => ['class' => 'text-uppercase'],
                'value'          => function ($model) {
                    return $model->panel ?: '';
                }
            ],
            'os'        => [
                'attribute' => 'os',
                'format'    => 'raw',
                'value'     => function ($model) use ($osImages) {
                    return OSFormatter::widget([
                        'osimages'  => $osImages,
                        'imageName' => $model->osimage
                    ]);
                }
            ],
            'discounts' => [
                'attribute' => 'discounts',
                'format'    => 'raw',
                'value'     => function ($model) {
                    return DiscountFormatter::widget([
                        'current' => $model->discounts['fee']['current'],
                        'next'    => $model->discounts['fee']['next'],
                    ]);
                }
            ],
            'actions'   => [
                'class'    => ActionColumn::className(),
                'template' => '{view} {block} {delete} {update}', // {state}
                'header'   => Yii::t('app', 'Actions'),
                'buttons'  => [
                    'block' => function ($url, $model, $key) {
                        return Html::a('Close', ['block', 'id' => $model->id]);
                    },
                ],
            ],
            'expires'   => [
                'filter'        => false,
                'format'        => 'raw',
                'headerOptions' => ['style' => 'width: 1em'],
                'value'         => function ($model) {
                    return Expires::widget(compact('model'));
                },
            ]
        ];
    }
}
