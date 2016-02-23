<?php

use hipanel\widgets\ChartJs;
use yii\helpers\Html;

list($labels, $data) = $model->groupUsesForCharts();

?>

<div class="row">
    <div class="col-md-12">
        <?php
        if ($data === []) {
            echo Yii::t('hipanel/server', 'Traffic consumption history is not available for this server');
        } else {
            echo Html::tag('div', ChartJs::widget([
                'type' => 'Line',
                'legend' => true,
                'data' => [
                    'labels' => array_values($labels),
                    'datasets' => [
                        [
                            'label' => Yii::t('hipanel/server', 'Total outgoing traffic, Gb'),
                            'fillColor' => "rgba(220,220,220,0.5)",
                            'strokeColor' => "rgba(220,220,220,1)",
                            'pointColor' => "rgba(220,220,220,1)",
                            'pointStrokeColor' => "#fff",
                            'data' => $data['server_traf']
                        ],
                        [
                            'label' => Yii::t('hipanel/server', 'Total incoming traffic, Gb'),
                            'fillColor' => "rgba(151,187,205,0.5)",
                            'strokeColor' => "rgba(151,187,205,1)",
                            'pointColor' => "rgba(151,187,205,1)",
                            'pointStrokeColor' => "#fff",
                            'data' => $data['server_traf_in']
                        ]
                    ]
                ],
                'clientOptions' => [
                    'bezierCurve' => false,
                    'responsive' => true,
                    'maintainAspectRatio' => true,
                ]
            ]), ['class' => 'traffic-chart-wrapper']);
        }

        ?>
    </div>
</div>
