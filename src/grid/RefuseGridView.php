<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use Yii;

class RefuseGridView extends \hipanel\grid\BoxedGridView
{
    protected function columns()
    {
        return array_merge(parent::columns(), [
            'user_comment' => [
                'filterAttribute' => 'user_comment_like',
                'value' => function ($model) {
                    return $model->user_comment;
                },
            ],
            'server' => [
                'label' => Yii::t('hipanel:server', 'Server'),
                'value' => function ($model) {
                    return $model->params['name'] ?: $model->params['server'];
                },
            ],
            'time' => [
                'filter' => false,
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->time);
                },
            ],
        ]);
    }
}
