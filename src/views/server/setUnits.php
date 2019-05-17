<?php

use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/** @var \hipanel\modules\server\models\Server $model */
/** @var \hipanel\modules\server\models\Server[] $models */
$model->hardwareSettings->scenario = 'set-units';
$this->title = Yii::t('hipanel:server', 'Set units');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'set-units-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-hw-form', 'scenario' => $model->scenario]),
]) ?>

<div class="row">
    <div class="col-md-6">
        <div class="container-items">
            <div class="item">
                <div class="box box-widget">
                    <div class="box-body no-padding">
                        <div class="panel panel-default" style="box-shadow: none; border-radius: 0;">
                            <div class="panel-heading"><?= Yii::t('hipanel:server', 'Selected servers') ?></div>
                            <div class="panel-body">
                                <?= ArraySpoiler::widget([
                                    'data' => $models,
                                    'visibleCount' => count($models),
                                    'formatter' => function ($model) {
                                        return Html::tag('span', $model->name, ['class' => 'label label-default']);
                                    },
                                    'delimiter' => ',&nbsp; ',
                                ]) ?>
                            </div>
                        </div>
                        <div style="padding: 0 1rem;">
                            <?php $model->hardwareSettings->units = null;
                            echo $form->field($model->hardwareSettings, 'units') ?>
                        </div>
                        <?php foreach ($models as $model) : ?>
                            <?= Html::activeHiddenInput($model->hardwareSettings, "[{$model->hardwareSettings->id}]id") ?>
                        <?php endforeach ?>
                    </div>
                </div>
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

