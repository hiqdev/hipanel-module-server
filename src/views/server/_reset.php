<?php
use yii\helpers\Html;
use hipanel\widgets\ModalButton;

ModalButton::begin([
    'model'    => $model,
    'scenario' => 'reset',
    'button'   => [
        'label'    => Yii::t('app', 'Reset'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('app', 'Confirm server power reset')),
        'headerOptions' => ['class' => 'label-warning'],
        'footer'        => [
            'label'             => Yii::t('app', 'Reset power'),
            'data-loading-text' => Yii::t('app', 'Resetting power...'),
            'class'             => 'btn btn-warning',
        ]
    ]
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>
    </div>
    <p><?= Yii::t('app',
            'Power reset will interrupt all processes on the server in a dangerous way. Always try to reboot it, before resetting. Are you sure you want to reset power of the server?') ?></p>

<?php ModalButton::end();