<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;

echo Html::beginForm(['reboot'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
echo Html::hiddenInput('id', $model->id);
Modal::begin([
    'toggleButton'  => [
        'label'    => Yii::t('app', 'Reboot'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server reboot')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer'        => Html::button(Yii::t('app', 'Reboot'), [
        'class'             => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Rebooting...'),
        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit();")
    ])
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

        <p><?= Yii::t('app', 'Reboot will interrupt all processes on the server. Are you sure you want to reset the server?') ?></p>
    </div>
<?php Modal::end();
echo Html::endForm();