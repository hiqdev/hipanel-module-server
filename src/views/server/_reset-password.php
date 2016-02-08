<?php
use yii\helpers\Html;
use hipanel\widgets\ModalButton;

echo ModalButton::widget([
    'model'    => $model,
    'scenario' => 'reset-password',
    'button'   => ['label' => '<i class="fa fa-refresh"></i>' . Yii::t('hipanel/server', 'Reset root password')],
    'body'     => Yii::t('hipanel/server',
        'Are you sure you want to reset the root password on {name} server? You will get your new root password on the e-mail.',
        ['name' => $model->name]),
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('hipanel/server', 'Confirm root password resetting')),
        'headerOptions' => ['class' => 'label-danger'],
        'footer'        => [
            'label'             => Yii::t('hipanel/server', 'Reset root password'),
            'data-loading-text' => Yii::t('hipanel/server', 'Resetting...'),
            'class'             => 'btn btn-danger',
        ]
    ]
]);

