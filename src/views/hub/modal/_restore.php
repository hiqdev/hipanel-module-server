<?php

use hipanel\modules\server\models\Hub;
use hipanel\widgets\BulkOperation;

/** @var array $models */
/** @var Hub $model */

?>

<?= BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'restore',
    'affectedObjects' => Yii::t('hipanel:server:hub', 'Затронутые свитчи'),
    'hiddenInputs' => ['id'],
    'submitButton' => Yii::t('hipanel:server:hub', 'Restore'),
    'submitButtonOptions' => ['class' => 'btn btn-success'],
]) ?>

