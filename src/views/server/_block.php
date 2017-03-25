<?php

use hipanel\helpers\Url;
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

?>

<?php if (!$model->isBlocked) : ?>
    <?php $modalButton = ModalButton::begin([
        'model' => $model,
        'scenario' => 'enable-block',
        'button' => ['label' => '<i class="fa fa-lock fa-fw"></i>' . Yii::t('hipanel:server', 'Block server')],
        'form' => [
            'enableAjaxValidation'   => true,
            'validationUrl'          => Url::toRoute(['validate-form', 'scenario' => 'enable-block']),
        ],
        'modal' => [
            'header' => Html::tag('h4', Yii::t('hipanel:server', 'Confirm server blocking')),
            'headerOptions' => ['class' => 'label-danger'],
            'footer' => [
                'label' => Yii::t('hipanel:server', 'Block'),
                'data-loading-text' => Yii::t('hipanel:server', 'Blocking...'),
                'class' => 'btn btn-danger',
            ],
        ],
    ]) ?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('hipanel:server', 'This will immediately reject new SSH, FTP and WEB connections to the server!') ?></h4>
    </div>

    <?= $modalButton->form->field($model, 'type')->dropDownList($blockReasons) ?>
    <?= $modalButton->form->field($model, 'comment') ?>

    <?php $modalButton->end() ?>
<?php else : ?>
    <?php $modalButton = ModalButton::begin([
        'model' => $model,
        'scenario' => 'disable-block',
        'button' => ['label' => '<i class="fa fa-unlock fa-fw"></i>' . Yii::t('hipanel:server', 'Unblock server')],
        'form' => [
            'enableAjaxValidation'   => true,
            'validationUrl'          => Url::toRoute(['validate-form', 'scenario' => 'disable-block']),
        ],
        'modal' => [
            'header' => Html::tag('h4', Yii::t('hipanel:server', 'Confirm server unblocking')),
            'headerOptions' => ['class' => 'label-info'],
            'footer' => [
                'label' => Yii::t('hipanel:server', 'Unblock'),
                'data-loading-text' => Yii::t('hipanel:server', 'Unblocking...'),
                'class' => 'btn btn-info',
            ],
        ],
    ]) ?>

    <?= $modalButton->form->field($model, 'comment') ?>

    <?php $modalButton->end() ?>
<?php endif ?>
