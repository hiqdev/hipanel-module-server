<?php

use hipanel\widgets\BulkOperation;
use hipanel\modules\server\grid\RefuseGridView;
use yii\data\ArrayDataProvider;

echo BulkOperation::widget(array_merge([
    'model' => $model,
    'models' => $models,
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
    ], $bulkOp));

