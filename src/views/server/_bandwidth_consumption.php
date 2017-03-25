<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;

/**
 * @var array $labels
 * @var array $data
 */
?>

<div class="row bandwidth-consumption-chart-wrapper">
    <div class="col-md-12">
        <?php
        if ($data === []) {
            echo Yii::t('hipanel:server', 'Bandwidth consumption history is not available for this server');
        } else {
            echo Html::tag('div', ChartJs::widget([
                'id' => 'bandwidth_consumption_chart',
                'type' => 'line',
                'data' => [
                    'labels' => array_values($labels),
                    'datasets' => [
                        [
                            'label' => Yii::t('hipanel:server', '95th percentile for outgoing bandwidth, Mbit/s'),
                            'backgroundColor' => 'rgba(139, 195, 74, 0.5)',
                            'borderColor' => 'rgba(139, 195, 74, 1)',
                            'pointBackgroundColor' => 'rgba(139, 195, 74, 1)',
                            'pointBorderColor' => '#fff',
                            'data' => $data['server_traf95'],
                        ],
                        [
                            'label' => Yii::t('hipanel:server', '95th percentile for incoming bandwidth, Mbit/s'),
                            'backgroundColor' => 'rgba(151,187,205,0.5)',
                            'borderColor' => 'rgba(151,187,205,1)',
                            'pointBackgroundColor' => 'rgba(151,187,205,1)',
                            'pointBorderColor' => '#fff',
                            'data' => $data['server_traf95_in'],
                        ],
                    ],
                ],
                'clientOptions' => [
                    'bezierCurve' => false,
                    'responsive' => true,
                    'maintainAspectRatio' => true,
                ],
            ]));
        }

        ?>
    </div>
</div>
