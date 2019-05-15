<?php

use hipanel\modules\server\widgets\ChartOptions;
use hipanel\modules\server\widgets\ResourceConsumption;
use hipanel\widgets\Box;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:server', 'Resources');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($model->name), 'url' => ['view', 'id' => (int) $model->id]];
$this->params['breadcrumbs'][] = $this->title;

$iteration = 1;
?>
<div class="row">
    <?php if (empty($chartsData) && empty($chartsLabels)) : ?>
        <div class="col-md-6 col-md-offset-3">
            <p class="bg-warning text-center"
               style="padding: 3rem"><?= Yii::t('hipanel:server', 'No resources found') ?></p>
        </div>
    <?php else : ?>
        <?php foreach (ResourceConsumption::types() as $type => $label) : ?>
            <?php if (isset($chartsData[$type])) : ?>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $box = Box::begin(['renderBody' => false, 'options' => ['class' => 'box-widget']]);
                            $box->beginHeader();
                            echo $box->renderTitle(Yii::t('hipanel:server', $label));
                            $box->beginTools();
                            echo ChartOptions::widget([
                                'id' => $type,
                                'form' => [
                                    'action' => 'draw-chart',
                                ],
                                'hiddenInputs' => [
                                    'id' => ['value' => $model->id],
                                    'type' => ['value' => $type],
                                ],
                            ]);
                            $box->endTools();
                            $box->endHeader();
                            $box->beginBody();
                            echo $this->render('_consumption', [
                                'labels' => $chartsLabels,
                                'data' => $chartsData,
                                'consumptionBase' => $type,
                            ]);
                            $box->endBody();
                            $box->end();
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($iteration % 3 === 0) : ?>
                <?= Html::tag('div', '', ['class' => 'clearfix']) ?>
                <?php ++$iteration; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
