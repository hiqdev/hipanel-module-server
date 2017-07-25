<?php

use hipanel\modules\server\widgets\combo\ServerCombo;

?>
<div class="server-switcher" style="margin-bottom: 1em;">
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
