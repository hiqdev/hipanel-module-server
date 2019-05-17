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
    'scenario' => 'enable-block',
    'affectedObjects' => Yii::t('hipanel:server', 'Affected servers'),
    'hiddenInputs' => ['id', 'name'],
    'visibleInputs' => ['comment'],
    'submitButton' => Yii::t('hipanel', 'Enable block'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
    'dropDownInputs' => ['type' => $blockReasons],
]);
