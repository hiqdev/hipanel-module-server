<?php
use hipanel\helpers\Url;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'id' => 'bulk-approve-form',
    'action' => Url::toRoute('bulk-approve'),
    'enableAjaxValidation' => false,
]) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('hipanel/hosting', 'Affected VDS orders') ?></div>
        <div class="panel-body">
            <?= ArraySpoiler::widget([
                'data' => $models,
                'visibleCount' => count($models),
                'formatter' => function ($model) {
                    return $model->user_comment;
                },
                'delimiter' => ',&nbsp; '
            ]); ?>
        </div>
    </div>

<?php foreach ($models as $item) : ?>
    <?= Html::activeHiddenInput($item, "[$item->id]id") ?>
<?php endforeach; ?>

    <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'comment')->textInput([
                    'id' => 'change-approve-comment',
                    'name' => 'comment'
                ]); ?>
            </div>
    </div>

    <hr>
<?= Html::submitButton(Yii::t('hipanel', 'Approve'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
