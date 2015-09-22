<?php
use hipanel\helpers\Url;
use yii\helpers\Html;
use hipanel\widgets\ModalButton;

?>

<?php if (!$model->isBlocked) {
    $modalButton = ModalButton::begin([
        'model' => $model,
        'scenario' => 'enable-block',
        'button' => ['label' => '<i class="ion-locked"></i>' . Yii::t('app', 'Block server')],
        'form' => [
            'enableAjaxValidation'   => true,
            'validationUrl'          => Url::toRoute(['validate-form', 'scenario' => 'enable-block']),
        ],
        'modal' => [
            'header' => Html::tag('h4', Yii::t('app', 'Confirm server blocking')),
            'headerOptions' => ['class' => 'label-danger'],
            'footer' => [
                'label' => Yii::t('app', 'Block'),
                'data-loading-text' => Yii::t('app', 'Blocking...'),
                'class' => 'btn btn-danger',
            ]
        ]
    ]); ?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('app', 'This will immediately reject new SSH, FTP and WEB connections to the server!') ?></h4>
    </div>

    <?php echo $modalButton->form->field($model, 'type')->dropDownList($blockReasons); ?>
    <?php echo $modalButton->form->field($model, 'comment'); ?>

    <?php $modalButton->end();
} else {
    $modalButton = ModalButton::begin([
        'model' => $model,
        'scenario' => 'disable-block',
        'button' => ['label' => '<i class="ion-unlocked"></i>' . Yii::t('app', 'Unblock server')],
        'form' => [
            'enableClientValidation' => true,
            'validateOnBlur'         => true,
        ],
        'modal' => [
            'header' => Html::tag('h4', Yii::t('app', 'Confirm server unblocking')),
            'headerOptions' => ['class' => 'label-info'],
            'footer' => [
                'label' => Yii::t('app', 'Unblock'),
                'data-loading-text' => Yii::t('app', 'Unblocking...'),
                'class' => 'btn btn-info',
            ]
        ]
    ]); ?>

    <?php echo $modalButton->form->field($model, 'comment'); ?>

    <?php $modalButton->end();
}

