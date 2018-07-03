<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */
use hipanel\widgets\BulkOperation;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'clear-resources',
    'affectedObjects' => Yii::t('hipanel:server', 'Affected servers'),
    'hiddenInputs' => ['id', 'name'],
    'bodyWarning' => Yii::t('hipanel:server', 'This action is irreversable and will clear server resources usage history, including traffic and overuses.'),
    'submitButton' => Yii::t('hipanel:server', 'Clear resources'),
    'submitButtonOptions' => ['class' => 'btn btn-warning'],
]);
