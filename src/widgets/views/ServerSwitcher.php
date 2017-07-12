<?php

use hipanel\modules\server\widgets\combo\ServerCombo;
use yii\bootstrap\ActiveForm;

?>
<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'name')->widget(ServerCombo::class, [
    'inputOptions' => [
        'data' => [
            'allow-clear' => 'false'
        ],
    ],
])->label(false) ?>
<?php ActiveForm::end() ?>
