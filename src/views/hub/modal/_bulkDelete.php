<?php

use hipanel\widgets\BulkOperation;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'delete',
    'affectedObjects' => Yii::t('hipanel:server:hub', 'Affected switches'),
    'hiddenInputs' => ['id'],
    'bodyWarning' => Yii::t('hipanel:server:hub', 'This action is irreversible!'),
    'submitButton' => Yii::t('hipanel', 'Delete'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
]);
