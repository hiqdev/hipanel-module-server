<?php

use hipanel\widgets\BulkOperation;

echo BulkOperation::widget(array_metge([
    'model' => $model,
    'models' => $models,
    'affectedObjects' => Yii::t('hipanel:server', 'Affected servers'),
    'scenario' => 'enable-block',
    'hiddenInputs' => ['id', 'name'],
    'visibleInputs' => ['comment'],
    'submitButton' => Yii::t('hipanel', 'Enable block'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
], $bulkOp));

