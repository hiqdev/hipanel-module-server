<?php

use hipanel\helpers\Url;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:server', 'Hardware properties');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-4">
        <?php $form = ActiveForm::begin([
            'id' => 'hw-form',
            'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
        ]); ?>

        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= Yii::t('hipanel:server', 'Change hardware properties') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'summary') ?>
                <?= $form->field($model, 'order_no') ?>
                <?= $form->field($model, 'brand') ?>
                <?= $form->field($model, 'box') ?>
                <?= $form->field($model, 'cpu') ?>
                <?= $form->field($model, 'ram') ?>
                <?= $form->field($model, 'motherboard') ?>
                <?= $form->field($model, 'hdd') ?>
                <?= $form->field($model, 'hotswap') ?>
                <?= $form->field($model, 'raid') ?>
                <?= $form->field($model, 'units') ?>
                <?= $form->field($model, 'note') ?>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php $form->end() ?>
    </div>
    <div class="col-md-4">

        <?php if (Yii::getAlias('@part', false) && Yii::$app->user->can('support')) : ?>
            <div class="row">
                <?php Pjax::begin(['enablePushState' => false]) ?>
                <div class="col-md-12">
                    <?php $box = Box::begin(['renderBody' => false]) ?>
                    <?php $box->beginHeader() ?>
                    <?= $box->renderTitle(Yii::t('hipanel:server', 'Configuration')) ?>
                    <?php $box->beginTools() ?>
                    <?= Html::a(Yii::t('hipanel', 'Details'), Url::toSearch('part', ['dst_name_like' => $model->name]), ['class' => 'btn btn-default btn-xs']) ?>
                    <?php $box->endTools() ?>
                    <?php $box->endHeader() ?>
                    <?php $box->beginBody() ?>
                    <?php $url = Url::to(['@part/render-object-parts', 'id' => $model->id]) ?>
                    <?= Html::tag('div', '', ['class' => 'server-parts']) ?>
                    <?php $this->registerJs("$('.server-parts').load('$url', function () {
                                $(this).closest('.box').find('.overlay').remove();
                            });") ?>
                    <?php $box->endBody() ?>
                    <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                    <?php $box->end() ?>
                </div>
                <?php Pjax::end() ?>
            </div>
        <?php endif ?>
    </div>
</div>
