<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;

echo Html::a('<i class="fa fa-refresh"></i>' . Yii::t('app', 'Reset root password'), '#', [
    'data-toggle' => 'modal',
    'data-target' => "#modal_{$model->id}_reset-password",
]);

echo Html::beginForm(['reset-password'], "POST", ['data' => ['pjax' => 1, 'pjax-push' => 0], 'class' => 'inline']);
echo Html::activeHiddenInput($model, 'id');
Modal::begin([
    'id'            => "modal_{$model->id}_reset-password",
    'toggleButton'  => false,
    'header'        => Html::tag('h4', Yii::t('app', 'Confirm root password resetting')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer'        => Html::button(Yii::t('app', 'Reset root password'), [
        'class'             => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Resetting...'),
        'onClick'           => new \yii\web\JsExpression("
                                    $(this).closest('form').trigger('submit');
                                    $(this).button('loading');
                                ")
    ])
]);
echo Yii::t('app', 'Are you sure you want to reset the root password on {name} server? You will get your new root password on the e-mail.',
    ['name' => $model->name]);
Modal::end();
echo Html::endForm();
