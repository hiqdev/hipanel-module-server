<?php

use hipanel\modules\server\models\AssignSwitchInterface;
use hipanel\modules\server\widgets\combo\HubCombo;
use hipanel\widgets\ArraySpoiler;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var ActiveForm $form */
/** @var AssignSwitchInterface $model */
/** @var AssignSwitchInterface[] $models */

$this->registerCss(<<<CSS
.box-body .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    align-items: flex-start;
}
CSS
);
?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-widget">
            <div class="box-header">
                <?= $form->field($model, 'rack_id')->widget(HubCombo::class, ['hubType' => HubCombo::RACK])->hint(Yii::t('hipanel:server', 'Assign the rack to all selected objects')) ?>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel:server', 'Selected objects:') ?></h3>
            </div>
            <div class="box-body">
                <div class="grid">
                    <?= ArraySpoiler::widget([
                        'data' => $models,
                        'visibleCount' => count($models),
                        'formatter' => static fn($model) => Html::a(
                            ($model->getBinding('rack') ? Html::tag('span', Html::encode($model->getBinding('rack')->switch), ['class' => 'badge bg-purple']) : '') .
                            Html::tag('i', null, ['class' => 'fa fa-server']) .
                            Html::encode($model->name),
                            ['view', 'id' => $model->id],
                            ['target' => '_blank', 'class' => 'btn btn-app text-bold col']
                        ),
                        'delimiter' => '',
                    ]) ?>
                </div>
                <?php foreach ($models as $model) : ?>
                    <?= Html::activeHiddenInput($model, "[{$model->id}]id") ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
