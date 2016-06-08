<?php

/*
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\grid\ActionColumn;
use hipanel\modules\server\helpers\ServerHelper;
use hipanel\modules\server\widgets\OSFormatter;
use Yii;

class RefuseGridView extends \hipanel\grid\BoxedGridView
{
    public static function defaultColumns()
    {
        return array_merge(parent::defaultColumns(), [
            'user_comment' => [
                'filterAttribute' => 'user_comment_like',
                'value' => function ($model) {
                    return $model->user_comment;
                }
            ],
            'server' => [
                'label' => Yii::t('hipanel/server', 'Server'),
                'value' => function ($model) {
                    return $model->params['name'] ?: $model->params['server'];
                }
            ],
            'time' => [
                'filter' => false,
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->time);
                }
            ],
        ]);
    }
}
