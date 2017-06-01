<?php

use hipanel\widgets\BulkOperation;
use hipanel\modules\server\grid\PreOrderGridView;
use yii\data\ArrayDataProvider;

echo BulkOperation::widget(array_merge([
    'model' => $model,
    'models' => $models,
    'affectedObjects' => Yii::t('hipanel:server', 'Affected VDS'),
    'panelBody' => PreOrderGridView::widget([
        'dataProvider' => new ArrayDataProvider(['allModels' => $models, 'pagination' => false]),
        'boxed' => false,
        'columns' => [
            'client', 'user_comment', 'tech_details', 'time',
        ],
        'layout' => '{items}',
    ]),
    'hiddenInputs' => ['id'],
    'visibleInputs' => ['comment'],
    ], $bulkOp));

