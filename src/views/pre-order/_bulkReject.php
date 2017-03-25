<?php
use hipanel\helpers\Url;
use hipanel\modules\server\models\Change;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var Change[] $models
 */
?>
<?php $form = ActiveForm::begin([
    'id' => 'bulk-reject-form',
    'action' => Url::toRoute('bulk-reject'),
    'enableAjaxValidation' => false,
]) ?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('hipanel:server', 'Affected VDS orders') ?></div>
    <div class="panel-body">
            <?= \hipanel\modules\server\grid\PreOrderGridView::widget([
                'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $models, 'pagination' => false]),
                'boxed' => false,
                'columns' => [
                    'client',
                    'user_comment',
                    'tech_details',
                    'time',
                ],
                'layout' => '{items}',
            ]) ?>
    </div>
</div>

<?php foreach ($models as $item) : ?>
    <?= Html::activeHiddenInput($item, "[$item->id]id") ?>
<?php endforeach; ?>

<div class="row">
    <div class="col-sm-6">
        <?= $form->field($model, 'comment')->textInput([
            'id' => 'change-reject-comment',
            'name' => 'comment',
        ]); ?>
    </div>
</div>

<hr>
<?= Html::submitButton(Yii::t('hipanel:finance:change', 'Reject'), ['class' => 'btn btn-danger']) ?>

<?php ActiveForm::end() ?>
