<?php
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

ModalButton::begin([
    'model'    => $model,
    'scenario' => 'reboot',
    'button'   => [
        'label'    => Yii::t('hipanel/server', 'Reboot'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('hipanel/server', 'Confirm server reboot')),
        'headerOptions' => ['class' => 'label-warning'],
        'footer'        => [
            'label'             => Yii::t('hipanel/server', 'Reboot'),
            'data-loading-text' => Yii::t('hipanel/server', 'Rebooting...'),
            'class'             => 'btn btn-warning',
        ]
    ]
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('hipanel/server', 'This may cause data loose!') ?></h4>

        <p><?= Yii::t('hipanel/server', 'Reboot will interrupt all processes on the server. Are you sure you want to reset the server?') ?></p>
    </div>

<?php ModalButton::end();
