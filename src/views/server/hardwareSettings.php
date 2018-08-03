<?php

use hipanel\helpers\Url;
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
    <div class="col-md-6">
        <?php if (Yii::getAlias('@part', false) && Yii::$app->user->can('support')) : ?>
            <div class="row">
                <?php Pjax::begin(['enablePushState' => false]) ?>
                <div class="col-md-12">
                    <?php $box = Box::begin(['renderBody' => false, 'bodyOptions' => ['class' => 'no-padding']]) ?>
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
