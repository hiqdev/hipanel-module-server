<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;








echo Html::a('<i class="fa fa-trash-o"></i>' . Yii::t('app', 'Delete'), '#', [
    'data-toggle' => 'modal',
    'data-target' => "#modal_{$model->id}_delete",
]);

echo Html::beginForm(['delete'], "POST", ['data' => ['pjax' => 1, 'pjax-push' => 0], 'class' => 'inline']);
echo Html::activeHiddenInput($model, 'id');
Modal::begin([
    'id'            => "modal_{$model->id}_delete",
    'toggleButton'  => false,
    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server deleting')),
    'headerOptions' => ['class' => 'label-danger'],
    'footer'        => Html::button(Yii::t('app', 'Delete server'), [
        'class'             => 'btn btn-danger',
        'data-loading-text' => Yii::t('app', 'Deleting server...'),
        'onClick'           => new \yii\web\JsExpression("
            $(this).closest('form').trigger('submit');
            $(this).button('loading');
        ")
    ])
]);
echo Yii::t('app', 'Are you sure you want to delete server {name}? You will loose everything!', ['name' => $model->name]);
Modal::end();
echo Html::endForm();


//$modal = ModalButton::widget([
//    'model'       => $model,
//    // false - не делать форму
//    'formOptions' => [
//        'action' => 'delete',
//        'type' => 'POST',
//    ],
//    'button' => [ // callback
//        'tag' => 'a',
//        'label' => 'DELETE',
//    ],
//    'buttonPosition' => ModalButton::BUTTON_OUTSIDE, // BUTTON_INSIDE, BUTTON_IN_MODAL
//    'body' => 'Text',
//    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server deleting')),
//    'footer'        => Html::button(Yii::t('app', 'Delete server'), [
//        'class'             => 'btn btn-danger',
//        'data-loading-text' => Yii::t('app', 'Deleting server...'),
//        'onClick'           => new \yii\web\JsExpression("
//            $(this).closest('form').trigger('submit');
//            $(this).button('loading');
//        "),
//    ])
//]);