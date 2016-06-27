<?php

use hipanel\modules\hosting\grid\IpGridView;
use hipanel\widgets\Box;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;

if (Yii::getAlias('@ip', false)) : ?>
    <?php $box = Box::begin(['renderBody' => false]) ?>
        <?php $box->beginHeader() ?>
            <?= $box->renderTitle(Yii::t('hipanel/server', 'IP addresses')) ?>
            <?php if (Yii::$app->user->can('support')) : ?>
                <?php $box->beginTools() ?>
                    <?= Html::a(
                        Yii::t('hipanel/server', 'Manage IP addresses'),
                        ['@ip', 'IpSearch' => ['server_in' => $model->name]],
                        ['class' => 'btn btn-default btn-sm']
                    ) ?>
                <?php $box->endTools() ?>
            <?php endif ?>
        <?php $box->endHeader() ?>
        <?php $box->beginBody() ?>
            <?= IpGridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->ips,
                    'pagination' => false,
                    'sort' => false,
                ]),
                'boxed' => false,
                'summary' => false,
                'columns' => ['ip', 'ptr', 'services'],
            ]) ?>
        <?php $box->endBody() ?>
    <?php $box->end() ?>
<?php endif ?>
