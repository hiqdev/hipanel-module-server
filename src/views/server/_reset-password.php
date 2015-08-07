<?php
use yii\helpers\Html;
use hipanel\widgets\ModalButton;

echo ModalButton::widget([
    'model'    => $model,
    'scenario' => 'reset-password',
    'button'   => ['label' => '<i class="fa fa-refresh"></i>' . Yii::t('app', 'Reset root password')],
    'body'     => Yii::t('app',
        'Are you sure you want to reset the root password on {name} server? You will get your new root password on the e-mail.',
        ['name' => $model->name]),
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('app', 'Confirm root password resetting')),
        'headerOptions' => ['class' => 'label-danger'],
        'footer'        => [
            'label'             => Yii::t('app', 'Reset root password'),
            'data-loading-text' => Yii::t('app', 'Resetting...'),
            'class'             => 'btn btn-danger',
        ]
    ]
]);

