<?php
use yii\helpers\Html;
use hipanel\widgets\ModalButton;

ModalButton::begin([
    'model'    => $model,
    'scenario' => 'reset',
    'button'   => [
        'label'    => Yii::t('hipanel/server', 'Reset'),
        'class'    => 'btn btn-default btn-block',
        'disabled' => !$model->isOperable(),
    ],
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('hipanel/server', 'Confirm server power reset')),
        'headerOptions' => ['class' => 'label-warning'],
        'footer'        => [
            'label'             => Yii::t('hipanel/server', 'Reset power'),
            'data-loading-text' => Yii::t('hipanel/server', 'Resetting power...'),
            'class'             => 'btn btn-warning',
        ]
    ]
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('hipanel/server', 'This may cause data loose!') ?></h4>
    </div>
    <p><?= Yii::t('hipanel/server',
            'Power reset will interrupt all processes on the server in a dangerous way. Always try to reboot it, before resetting. Are you sure you want to reset power of the server?') ?></p>

<?php ModalButton::end();
