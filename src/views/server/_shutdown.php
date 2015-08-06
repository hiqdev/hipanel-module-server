<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;

echo Html::beginForm(['shutdown'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
echo Html::hiddenInput('id', $model->id);
Modal::begin([
    'toggleButton'  => [
        'label'    => Yii::t('app', 'Shutdown'),
        'class'    => 'btn btn-default',
        'disabled' => !$model->isOperable(),
    ],
    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server shutdown')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer'        => Html::button(Yii::t('app', 'Shutdown'), [
        'class'             => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Shutting down...'),
        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
    ])
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

        <p><?= Yii::t('app', 'Shutdown will interrupt all processes on the server. Are you sure you want to shutdown the server?') ?></p>
    </div>
<?php Modal::end();
echo Html::endForm();