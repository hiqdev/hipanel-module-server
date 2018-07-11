<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */
use hipanel\modules\server\grid\RefuseGridView;
use hipanel\widgets\BulkOperation;
use yii\data\ArrayDataProvider;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'reject',
    'affectedObjects' => Yii::t('hipanel:server', 'Affected VDS'),
    'panelBody' => RefuseGridView::widget([
        'dataProvider' => new ArrayDataProvider(['allModels' => $models, 'pagination' => false]),
        'boxed' => false,
        'columns' => [
            'client', 'server', 'user_comment', 'time',
        ],
        'layout' => '{items}',
    ]),
    'hiddenInputs' => ['id'],
    'visibleInputs' => ['comment'],
    'submitButton' => Yii::t('hipanel:finance:change', 'Reject'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
]);
