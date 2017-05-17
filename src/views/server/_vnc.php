<?php
use hipanel\widgets\Pjax;
use hipanel\modules\server\widgets\VNCOperation;

Pjax::begin([
    'id' => 'server-vnc-pjax',
    'enablePushState' => false,
    'enableReplaceState' => false,
]);
echo VNCOperation::widget([
    'model' => $model,
]);
Pjax::end();
