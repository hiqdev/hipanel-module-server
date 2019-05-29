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

use hipanel\grid\BoxedGridView;
use yii\helpers\Html;

class ConfigGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
//            'actions' => [
//                'class' => MenuColumn::class,
//                'menuClass' => HubActionsMenu::class,
//            ],
            'name' => [
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a($model->name, ['@config/view', 'id' => $model->id]);
                },
            ]
        ]);
    }
}

