<?php

/** @var \hipanel\modules\server\models\Server $model */

/** @var array $consumption */

use hipanel\modules\server\helpers\ServerSort;
use yii\helpers\Html;

?>

<div id="resource-consumption-table" class="box box-widget">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('hipanel:server', 'Resource consumption') ?></h3>
        <div class="box-tools pull-right">
            <?= Html::a(Yii::t('hipanel:server', 'See chart'), [
                '@server/resources', 'id' => $model->id,
            ], ['class' => 'btn btn-box-tool']) ?>
        </div>
    </div>
    <div class="box-body no-padding">
        <div class="table-responsive">
            <table class="table table-hover table-condensed table-bordered">
                <thead>

                <tr>
                    <th rowspan="2"></th>
                    <th colspan="2" class="text-center"><?= Yii::t('hipanel:server', 'Tariff') ?></th>
                    <th colspan="2" class="text-center">
                        <?= Yii::t('hipanel:server', 'This month {month_year,date,MMMM yyyy}', ['month_year' => time()]) ?>
                    </th>
                    <th colspan="2" class="text-center">
                        <?= Yii::t('hipanel:server', 'Previous month {month_year,date,MMMM yyyy}', ['month_year' => strtotime('-1month')]) ?>
                    </th>
                </tr>

                <tr>
                    <th><?= Yii::t('hipanel:server', 'limit') ?></th>
                    <th><?= Yii::t('hipanel:server', 'price') ?></th>
                    <th><?= Yii::t('hipanel:server', 'value') ?></th>
                    <th><?= Yii::t('hipanel:server', 'overuse') ?></th>
                    <th><?= Yii::t('hipanel:server', 'value') ?></th>
                    <th><?= Yii::t('hipanel:server', 'overuse') ?></th>
                </tr>

                </thead>

                <tbody>

                <?php foreach (ServerSort::byConsumptionType()->values($model->consumption) as $item) : ?>
                    <tr>
                        <th class="text-right"><?= Yii::t('hipanel.server.consumption.type', $item->type) ?></th>
                        <td><?= $this->context->getFormatted($item, $item->limit) ?></td>
                        <td><?= Yii::$app->formatter->asCurrency($item->price, $item->currency) ?></td>
                        <td><?= $this->context->getFormatted($item, $item->currentValue) ?></td>
                        <td><?= $this->context->getFormatted($item, $item->currentOveruse) ?></td>
                        <td><?= $this->context->getFormatted($item, $item->previousValue) ?></td>
                        <td><?= $this->context->getFormatted($item, $item->previousOveruse) ?></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

