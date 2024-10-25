<?php

use hipanel\helpers\Url;
use hipanel\modules\server\models\Server;
use hipanel\widgets\Box;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var Server $model
 */

$url = Url::to(['@part/render-object-parts', 'id' => $model->id]);
$this->registerJs("$('.server-parts').load('$url', function () { $(this).closest('.box').find('.overlay').remove(); });");

?>

<?php if (Yii::getAlias('@part', false) && Yii::$app->user->can('server.update')) : ?>
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['@device/set-properties']),
                'id' => 'apc-form',
                'options' => ['style' => ['margin-bottom' => '1em']],
            ]) ?>
            <?= Html::activeHiddenInput($model->deviceProperties, 'id') ?>
            <?= $form->field($model->deviceProperties, 'average_power_consumption')->input('number', ['step' => 0.001]) ?>
            <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success', 'style' => 'display: inline-block']) ?>
            <?php ActiveForm::end() ?>
        </div>
        <div class="col-md-12">
            <?php $box = Box::begin([
                'renderBody' => false,
                'options' => ['class' => 'box-widget'],
                'bodyOptions' => ['class' => 'no-padding'],
            ]) ?>

            <?php $box->beginHeader() ?>
                <?= $box->renderTitle(Yii::t('hipanel:server', 'Configuration')) ?>
                <?php $box->beginTools() ?>
                    <?= Html::a(Yii::t('hipanel', 'Details'),
                        Url::toSearch('part', ['dst_name_like' => $model->name]),
                        ['class' => 'btn btn-default btn-xs', 'data-pjax' => 0]) ?>
                <?php $box->endTools() ?>
            <?php Box::endHeader() ?>
            <?php $box->beginBody() ?>
                <?= Html::tag('div', '', ['class' => 'server-parts']) ?>
            <?php $box->endBody() ?>

            <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>

            <?php $box::end() ?>
        </div>
    </div>
<?php endif ?>
