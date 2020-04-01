<?php

use hipanel\modules\hosting\grid\IpGridView;
use hipanel\modules\server\models\Server;
use hipanel\widgets\Box;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

/** @var Server $model */

if (Yii::getAlias('@ip', false) && $model->ips_num) : ?>
    <?php $box = Box::begin(['renderBody' => false]) ?>
        <?php $box->beginHeader() ?>
            <?= $box->renderTitle(Yii::t('hipanel:server', 'IP addresses'), $model->ips_num) ?>
            <?php if (Yii::$app->user->can('ip.read')) : ?>
                <?php $box->beginTools() ?>
                    <?= Html::a(
                        Yii::t('hipanel:server', 'Manage IP addresses'),
                        ['@ip', 'IpSearch' => ['server_in' => $model->name]],
                        ['class' => 'btn btn-default btn-sm']
                    ) ?>
                <?php $box->endTools() ?>
            <?php endif ?>
        <?php Box::endHeader() ?>
        <?php $box->beginBody() ?>
            <?= IpGridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->ips,
                    'pagination' => [
                        'pageSize' => 25,
                    ],
                    'sort' => false,
                ]),
                'layout' => '{items}',
                'boxed' => false,
                'summary' => false,
                'controllerUrl' => '@ip',
                'columns' => ['ip', 'ptr', 'services'],
            ]) ?>
        <?php $box->endBody() ?>
    <?php $box->end() ?>
<?php endif ?>
