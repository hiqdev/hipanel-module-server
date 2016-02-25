<?php

use hipanel\widgets\ChartJs;
use yii\helpers\Html;

/**
 * @var array $labels
 * @var array $data
 */

?>

<div class="row traffic-consumption-chart-wrapper">
    <div class="col-md-12">
        <?php
        if ($data === []) {
            echo Yii::t('hipanel/server', 'Traffic consumption history is not available for this server');
        } else {
            echo Html::tag('div', ChartJs::widget([
                'id' => 'traffic_consumption_chart',
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
            ]));
        }
        ?>
    </div>
</div>
