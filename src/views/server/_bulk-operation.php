<?php

use hipanel\modules\server\widgets\BulkOperation;

echo BulkOperation::widget(array_merge([
    'model' => $model,
    'models' => $models,
    ], $bulkOp));

