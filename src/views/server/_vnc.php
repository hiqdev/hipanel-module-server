<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */
use hipanel\modules\server\widgets\VNCOperation;
use hipanel\widgets\Pjax;

Pjax::begin([
    'id' => 'server-vnc-pjax',
    'enablePushState' => false,
    'enableReplaceState' => false,
]);
echo VNCOperation::widget([
    'model' => $model,
]);
Pjax::end();
