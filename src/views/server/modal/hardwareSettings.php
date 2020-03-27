<?php

use hipanel\helpers\Url;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

?>

<?php if (Yii::getAlias('@part', false) && Yii::$app->user->can('support')) : ?>
    <div class="row">
        <?php Pjax::begin(['enablePushState' => false]) ?>
        <div class="col-md-12">
            <?php $box = Box::begin(['renderBody' => false, 'options' => ['class' => 'box-widget'], 'bodyOptions' => ['class' => 'no-padding']]) ?>
            <?php $box->beginHeader() ?>
            <?= $box->renderTitle(Yii::t('hipanel:server', 'Configuration')) ?>
            <?php $box->beginTools() ?>
            <?= Html::a(Yii::t('hipanel', 'Details'), Url::toSearch('part', ['dst_name_like' => $model->name]), ['class' => 'btn btn-default btn-xs', 'data-pjax' => 0]) ?>
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
            <?php $box::end() ?>
        </div>
        <?php Pjax::end() ?>
    </div>
<?php endif ?>
