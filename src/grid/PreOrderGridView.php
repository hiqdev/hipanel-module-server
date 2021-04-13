<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\grid\ActionColumn;
use hipanel\modules\server\helpers\ServerHelper;
use hipanel\modules\server\widgets\OSFormatter;
use Yii;

class PreOrderGridView extends \hipanel\grid\BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'tech_details' => [
                'format' => 'html',
                'label' => Yii::t('hipanel:finance:change', 'Operation details'),
                'value' => function ($model) {
                    $params = $model->params;

                    return OSFormatter::widget([
                        'osimages' => ServerHelper::getOsimages($params['tariff_type']),
                        'imageName' => $params['osimage'],
                        'infoCircle' => false,
                    ]);
                },
            ],
            'user_comment' => [
                'filterAttribute' => 'user_comment_like',
                'value' => function ($model) {
                    return $model->user_comment;
                },
            ],
            'tech_comment' => [
                'attribute' => 'tech_comment',
            ],
            'time' => [
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->time);
                },
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ]);
    }
}
