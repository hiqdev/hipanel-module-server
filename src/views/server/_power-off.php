<?php
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

ModalButton::begin([
    'model'    => $model,
    'scenario' => 'power-off',
    'button'   => [
        'label'    => Yii::t('hipanel/server', 'Power off'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('hipanel/server', 'Confirm server power off')),
        'headerOptions' => ['class' => 'label-warning'],
        'footer'        => [
            'label'             => Yii::t('hipanel/server', 'Power OFF'),
            'data-loading-text' => Yii::t('hipanel/server', 'Turning power OFF...'),
            'class'             => 'btn btn-warning',
        ]
    ]
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('hipanel/server', 'This may cause data loose!') ?></h4>

        <p><?= Yii::t('hipanel/server',
                'Power off will immediately interrupt all processes on the server in a dangerous way. Always try to shutdown it, before turning off the power. Are you sure you want to power off the server?') ?></p>
    </div>

<?php ModalButton::end();
