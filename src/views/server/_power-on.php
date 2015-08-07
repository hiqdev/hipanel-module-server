<?php
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

echo ModalButton::widget([
    'model'    => $model,
    'scenario' => 'power-on',
    'button'   => [
        'label'    => Yii::t('app', 'Power on'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'body'     => Yii::t('app', 'Turn ON server power?'),
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('app', 'Confirm server power ON')),
        'headerOptions' => ['class' => 'label-info'],
        'footer'        => [
            'label'             => Yii::t('app', 'Power ON'),
            'data-loading-text' => Yii::t('app', 'Turning power ON...'),
            'class'             => 'btn btn-info',
        ]
    ]
]);