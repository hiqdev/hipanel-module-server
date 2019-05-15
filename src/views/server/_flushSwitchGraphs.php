<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

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
