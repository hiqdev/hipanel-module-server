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
    'scenario' => 'delete',
    'affectedObjects' => Yii::t('hipanel:server', 'Affected servers'),
    'hiddenInputs' => ['id', 'name'],
    'bodyWarning' => Yii::t('hipanel:server', 'This action is irreversible and causes full data loss including backups!'),
    'submitButton' => Yii::t('hipanel', 'Delete'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
]);
