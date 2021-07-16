<?php

use hipanel\grid\GridView;
use hipanel\helpers\Url;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\models\Server;
use hipanel\widgets\Box;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var Server|Hub $model */
/** @var array $configAttrs */
/** @var bool $loadAjax */

?>

<div class="row">
    <div class="col-md-12">
        <?php $box = Box::begin(['renderBody' => false]) ?>

            <?php if ($loadAjax) : ?>
                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
            <?php endif ?>

            <?php $box->beginHeader() ?>
                <?= $box->renderTitle(Yii::t('hipanel:server', 'Configuration')) ?>
                <?php $box->beginTools(['class' => 'box-tools pull-right']) ?>
                    <?= Html::a(Yii::t('hipanel', 'Details'), Url::toSearch('part', ['dst_name_like' => $model->name]), ['class' => 'btn btn-box-tool']) ?>
                <?php $box->endTools() ?>
            <?php $box->endHeader() ?>

            <?php $box->beginBody() ?>
                <?= DetailView::widget([
                    'model' => $model->hardwareSettings,
                    'attributes' => $configAttrs,
                ]) ?>
                <?= $this->context->takeContent() ?>

                <?= GridView::widget([
                    'dataProvider' => new ArrayDataProvider([
                        'pagination' => false,
                        'allModels' => $this->context->getTotalsData(),
                        'sort' => false,
                    ]),
                    'summary' => false,
                    'columns' => [
                        [
                            'attribute' => 'company',
                            'label' => Yii::t('hipanel:server', 'Company'),
                        ],
                        [
                            'attribute' => 'count',
                            'label' => Yii::t('hipanel:server', 'Count'),
                        ],
                        [
                            'attribute' => 'sum',
                            'label' => Yii::t('hipanel:server', 'Totals'),
                        ],
                    ],
                ]) ?>

            <?php $box->endBody() ?>

        <?php Box::end() ?>
    </div>
</div>
