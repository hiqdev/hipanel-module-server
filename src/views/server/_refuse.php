<?php
use yii\helpers\Html;
use hipanel\widgets\ModalButton;

?>

<?php if ($model->autorenewal) {
    echo ModalButton::widget([
        'model' => $model,
        'scenario' => 'refuse',
        'button' => [
            'label' => Yii::t('hipanel/server', 'Refuse service'),
            'class' => 'btn btn-default',
        ],
        'modal' => [
            'header' => Html::tag('h4', Yii::t('hipanel/server', 'Confirm service refuse')),
            'headerOptions' => ['class' => 'label-danger'],
            'footer' => [
                'label' => Yii::t('hipanel/server', 'Refuse'),
                'data-loading-text' => Yii::t('hipanel/server', 'Refusing...'),
                'class' => 'btn btn-danger',
            ]
        ],
        'body' => function ($model) {
            if ($model->canFullRefuse()) {
                return Yii::t('hipanel/server', 'In case of service refusing, the server will be locked and turned off. All data on the server will be removed!');
            } else {
                return Yii::t('hipanel/server', 'In case of service refusing, the server will be locked and turned off {0, date, medium}. All data on the server will be removed!', Yii::$app->formatter->asTimestamp($model->expires));
            }
        }
    ]);
} elseif (in_array($model->state, $model->goodStates())) {
    echo ModalButton::widget([
        'model' => $model,
        'scenario' => 'enable-autorenewal',
        'button' => [
            'label' => Yii::t('hipanel/server', 'Renew service'),
            'class' => 'btn btn-default',
        ],
        'modal' => [
            'header' => Html::tag('h4', Yii::t('hipanel/server', 'Confirm service renewal')),
            'headerOptions' => ['class' => 'label-info'],
            'footer' => [
                'label' => Yii::t('hipanel/server', 'Renew'),
                'data-loading-text' => Yii::t('hipanel/server', 'Renewing...'),
                'class' => 'btn btn-info',
            ]
        ],
        'body' => Yii::t('hipanel/server', 'Are you sure, you want to renew the service?')
    ]);
}

