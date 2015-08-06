<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;

echo Html::beginForm(['power-on'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
echo Html::hiddenInput('id', $model->id);
Modal::begin([
    'toggleButton' => [
        'label'    => Yii::t('app', 'Power on'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'header'       => Html::tag('h4', Yii::t('app', 'Confirm server power ON')),
    'footer'       => Html::button(Yii::t('app', 'Power ON'), [
        'class'             => 'btn btn-info',
        'data-loading-text' => Yii::t('app', 'Turning power ON...'),
        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
    ])
]);
echo Yii::t('app', 'Turn ON server power?');
Modal::end();
echo Html::endForm();