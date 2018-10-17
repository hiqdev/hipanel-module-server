<?php

use hipanel\modules\dashboard\widgets\ObjectsCountWidget;
use hipanel\modules\dashboard\widgets\SearchForm;
use hipanel\modules\dashboard\widgets\SmallBox;
use hipanel\modules\server\models\ServerSearch;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
    <?php $box = SmallBox::begin([
        'boxTitle' => Yii::t('hipanel', 'Servers'),
        'boxIcon' => 'fa-server',
        'boxColor' => SmallBox::COLOR_TEAL,
    ]) ?>
    <?php $box->beginBody() ?>
    <?= ObjectsCountWidget::widget([
        'totalCount' => $totalCount['servers'],
        'ownCount' => $model->count['servers'],
    ]) ?>
    <br>
    <br>
    <?= SearchForm::widget([
        'formOptions' => [
            'id' => 'server-search',
            'action' => Url::to('@server/index'),
        ],
        'model' => new ServerSearch(),
        'attribute' => 'name_like',
        'buttonColor' => SmallBox::COLOR_TEAL,
    ]) ?>
    <?php $box->endBody() ?>
    <?php $box->beginFooter() ?>
    <?php if ($model->count['servers'] || Yii::$app->user->can('support')) : ?>
        <?= Html::a(Yii::t('hipanel', 'View') . $box->icon(), '@server/index', ['class' => 'small-box-footer']) ?>
    <?php endif ?>
    <?php if (Yii::$app->user->can('server.create')) : ?>
        <?= Html::a(Yii::t('hipanel', 'Create') . $box->icon('fa-plus'), '@server/create', ['class' => 'small-box-footer']) ?>
    <?php endif ?>
    <?php if (Yii::$app->user->can('deposit')) : ?>
        <?= Html::a(Yii::t('hipanel', 'Buy') . $box->icon('fa-shopping-cart'), '@server/buy', ['class' => 'small-box-footer']) ?>
    <?php endif ?>
    <?php $box->endFooter() ?>
    <?php $box::end() ?>
</div>

