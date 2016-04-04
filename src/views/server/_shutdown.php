<?php
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

ModalButton::begin([
    'model'    => $model,
    'scenario' => 'shutdown',
    'button'   => [
        'label'    => Yii::t('hipanel/server', 'Shutdown'),
        'class'    => 'btn btn-default btn-block',
        'disabled' => !$model->isOperable(),
    ],
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('hipanel/server', 'Confirm server shutdown')),
        'headerOptions' => ['class' => 'label-warning'],
        'footer'        => [
            'label'             => Yii::t('hipanel/server', 'Shutdown'),
            'data-loading-text' => Yii::t('hipanel/server', 'Shutting down...'),
            'class'             => 'btn btn-warning',
        ]
    ]
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('hipanel/server', 'This may cause data loose!') ?></h4>
    </div>
    <p><?= Yii::t('hipanel/server', 'Shutdown will interrupt all processes on the server. Are you sure you want to shutdown the server?') ?></p>

<?php ModalButton::end();
