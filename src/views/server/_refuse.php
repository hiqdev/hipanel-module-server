<?php
use yii\helpers\Html;
use hipanel\widgets\ModalButton;

?>

<?php if ($model->autorenewal) {
    echo ModalButton::widget([
        'model' => $model,
        'scenario' => 'refuse',
        'button' => [
            'label' => Yii::t('app', 'Refuse service'),
            'class' => 'btn btn-default',
        ],
        'modal' => [
            'header' => Html::tag('h4', Yii::t('app', 'Confirm service refuse')),
            'headerOptions' => ['class' => 'label-danger'],
            'footer' => [
                'label' => Yii::t('app', 'Refuse'),
                'data-loading-text' => Yii::t('app', 'Refusing...'),
                'class' => 'btn btn-danger',
            ]
        ],
        'body' => function ($model) {
            if ($model->canFullRefuse()) {
                return Yii::t('app', 'In case of service refusing, the server will be locked and turned off. All data on the server will be removed!');
            } else {
                return Yii::t('app', 'In case of service refusing, the server will be locked and turned off {0, date, medium}. All data on the server will be removed!', strtotime($model->expires));
            }
        }
    ]);
} elseif (in_array($model->state, $model->goodStates())) {
    echo ModalButton::widget([
        'model' => $model,
        'scenario' => 'enable-autorenewal',
        'button' => [
            'label' => Yii::t('app', 'Renew service'),
            'class' => 'btn btn-default',
        ],
        'modal' => [
            'header' => Html::tag('h4', Yii::t('app', 'Confirm service renewal')),
            'headerOptions' => ['class' => 'label-info'],
            'footer' => [
                'label' => Yii::t('app', 'Renew'),
                'data-loading-text' => Yii::t('app', 'Renewing...'),
                'class' => 'btn btn-info',
            ]
        ],
        'body' => Yii::t('app', 'Are you sure, you want to renew the service?')
    ]);
}

