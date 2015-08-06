<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;

echo Html::beginForm(['reset'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
echo Html::hiddenInput('id', $model->id);
Modal::begin([
    'toggleButton'  => [
        'label'    => Yii::t('app', 'Reset'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server reset')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer'        => Html::button(Yii::t('app', 'Reset'), [
        'class'             => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Resetting...'),
        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
    ])
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>
        <p><?= Yii::t('app', 'Power reset will interrupt all processes on the server in a dangerous way. Always try to reboot it, before resetting. Are you sure you want to reboot the server?') ?></p>
    </div>
<?php Modal::end();
echo Html::endForm();