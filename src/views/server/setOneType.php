<?php

use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/** @var \hipanel\modules\server\models\Server $model */
/** @var \hipanel\modules\server\models\Server[] $models */
$this->title = Yii::t('hipanel:server', 'Set one type to many servers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'update-order-no-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'set-type']),
]) ?>

<div class="container-items">
    <div class="item">
        <div class="box">
            <div class="box-header with-border">
                <?= ArraySpoiler::widget([
                    'data' => $models,
                    'visibleCount' => count($models),
                    'formatter' => function ($model) {
                        return Html::tag('span', Html::encode($model->name), ['class' => 'label label-default']);
                    },
                    'delimiter' => ',&nbsp; ',
                ]) ?>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        <?php $model->type = null; echo $form->field($model, 'type')->dropDownList($model->typeOptions, ['prompt' => '--']) ?>
                    </div>
                </div>
                <?php foreach ($models as $model) : ?>
                    <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
