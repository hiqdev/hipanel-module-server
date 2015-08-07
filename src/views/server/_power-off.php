<?php
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

ModalButton::begin([
    'model'    => $model,
    'scenario' => 'power-off',
    'button'   => [
        'label'    => Yii::t('app', 'Power off'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('app', 'Confirm server power off')),
        'headerOptions' => ['class' => 'label-warning'],
        'footer'        => [
            'label'             => Yii::t('app', 'Power OFF'),
            'data-loading-text' => Yii::t('app', 'Turning power OFF...'),
            'class'             => 'btn btn-warning',
        ]
    ]
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

        <p><?= Yii::t('app',
                'Power off will immediately interrupt all processes on the server in a dangerous way. Always try to shutdown it, before turning off the power. Are you sure you want to power off the server?') ?></p>
    </div>

<?php ModalButton::end();