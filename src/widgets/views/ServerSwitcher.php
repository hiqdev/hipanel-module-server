<?php

use hipanel\modules\server\widgets\combo\ServerCombo;
use yii\bootstrap\ActiveForm;

?>
<div class="server-switcher">
<?= ServerCombo::widget([
    'model' => new \yii\base\DynamicModel(['name']),
    'attribute' => 'name',
    'hasId' => true,
    'formElementSelector' => '.server-switcher',
    'pluginOptions' => [
        'select2Options' => [
            'placeholder' => Yii::t('hipanel:server', 'Fast navigation to another server'),
            'allowClear' => false,
        ],
    ],
]) ?>
</div>
