<?php
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

ModalButton::begin([
    'model'    => $model,
    'scenario' => 'shutdown',
    'button'   => [
        'label'    => Yii::t('app', 'Shutdown'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('app', 'Confirm server shutdown')),
        'headerOptions' => ['class' => 'label-warning'],
        'footer'        => [
            'label'             => Yii::t('app', 'Shutdown'),
            'data-loading-text' => Yii::t('app', 'Shutting down...'),
            'class'             => 'btn btn-warning',
        ]
    ]
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>
    </div>
    <p><?= Yii::t('app', 'Shutdown will interrupt all processes on the server. Are you sure you want to shutdown the server?') ?></p>

<?php ModalButton::end();