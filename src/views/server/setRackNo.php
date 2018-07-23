<?php

use hipanel\modules\server\widgets\combo\HubCombo;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/** @var \hipanel\modules\server\models\Server $model */
/** @var \hipanel\modules\server\models\Server[] $models */

$model->hardwareSettings->scenario = 'set-rack-no';
$this->title = Yii::t('hipanel:server', 'Set Rack No.');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$model->rack_id = $model->rack_port = null;
?>

<?php $form = ActiveForm::begin([
    'id' => 'set-rack-no-form',
    'layout' => 'inline',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-hw-form', 'scenario' => $model->scenario]),
]) ?>

<div class="container-items">
    <div class="item">
        <div class="box">
            <div class="box-header with-border">
                <?= ArraySpoiler::widget([
                    'data' => $models,
                    'visibleCount' => count($models),
                    'formatter' => function ($model) {
                        return Html::tag('span', $model->name, ['class' => 'label label-default']);
                    },
                    'delimiter' => ',&nbsp; ',
                ]) ?>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= Html::label($model->getAttributeLabel('rack')) ?>
                        <br>
                        <?= $form->field($model, "rack_id")->widget(HubCombo::class, ['hubType' => HubCombo::RACK]) ?>
                        <?= $form->field($model, "rack_port") ?>
                    </div>
                </div>
                <?php foreach ($models as $model) : ?>
                    <?= Html::activeHiddenInput($model, "[{$model->id}]id") ?>
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

