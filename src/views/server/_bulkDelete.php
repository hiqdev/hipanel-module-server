<?php
use hipanel\helpers\Url;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'id' => 'bulk-delete-form',
    'action' => Url::toRoute('delete'),
    'enableAjaxValidation' => false,
]) ?>

    <div class="callout callout-warning">
        <h4><?= Yii::t('hipanel:server', 'This action is irreversible and causes full data loss including backups!') ?></h4>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('hipanel:server', 'Affected servers') ?></div>
        <div class="panel-body">
            <?= ArraySpoiler::widget([
                'data' => $models,
                'visibleCount' => count($models),
                'formatter' => function ($model) {
                    return $model->name;
                },
                'delimiter' => ',&nbsp; ',
            ]); ?>
        </div>
    </div>

<?php foreach ($models as $item) : ?>
    <?= Html::activeHiddenInput($item, "[$item->id]id") ?>
<?php endforeach; ?>

<?= Html::submitButton(Yii::t('hipanel', 'Delete'), ['class' => 'btn btn-danger']) ?>

<?php ActiveForm::end() ?>
