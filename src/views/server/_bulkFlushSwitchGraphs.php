<?php

use hipanel\widgets\BulkOperation;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'flush-switch-graphs',
    'affectedObjects' => Yii::t('hipanel:server', 'Affected servers'),
    'hiddenInputs' => ['id', 'name'],
    'bodyWarning' => Yii::t('hipanel:server', 'This action irreversibly clears network graphics collected from the network switch port, assigned to the server.'),
    'submitButton' => Yii::t('hipanel:server', 'Flush'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
]);

