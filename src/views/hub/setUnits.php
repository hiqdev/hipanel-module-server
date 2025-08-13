<?php

use hipanel\modules\server\models\Hub;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/** @var Hub $model */
/** @var Hub[] $models */
$model->hardwareSettings->scenario = 'set-units';
$this->title = Yii::t('hipanel:server', 'Set units');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'set-units-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-hw-form', 'scenario' => $model->hardwareSettings->scenario]),
]) ?>

<?php

$unitsCount = $model->hardwareSettings->units ?? 0;
$unitsEqual = array_reduce($models, static function (bool $result, Hub $hub) use ($unitsCount): bool {
    return $result && ($hub->hardwareSettings->units ?? 0) === $unitsCount;
}, true);

?>

<div class="row">
    <div class="col-md-6">
        <div class="container-items">
            <div class="item">
                <div class="box box-widget">
                    <div class="box-body no-padding">
                        <div class="panel panel-default" style="box-shadow: none; border-radius: 0;">
                            <div class="panel-heading"><?= Yii::t('hipanel:server', 'Selected switches') ?></div>
                            <div class="panel-body">
                                <?php if (!$unitsEqual) : ?>
                                    <div class="callout callout-info"><?= Yii::t('hipanel:server', 'Switches have different height!') ?></div>
                                <?php endif ?>
                                <?= ArraySpoiler::widget([
                                    'data' => $models,
                                    'visibleCount' => count($models),
                                    'formatter' => static function (Hub $model) use ($unitsEqual): string {
                                        $value = Html::encode($model->name);
                                        if (!$unitsEqual) {
                                            $size = $model->hardwareSettings->units ?? '?';
                                            $value .= " &ndash; {$size}U";
                                        }

                                        return Html::tag('span', $value, ['class' => 'label label-default']);
                                    },
                                    'delimiter' => ' &nbsp; ',
                                ]) ?>
                            </div>
                        </div>
                        <div style="padding: 0 1rem;">
                            <?= $form->field($model->hardwareSettings, 'units')->textInput([
                                'value' => $unitsEqual ? $model->hardwareSettings->units : null
                            ]) ?>
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

