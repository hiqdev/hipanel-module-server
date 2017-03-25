<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;

/**
 * @var array $labels
 * @var array $data
 */
$this->registerCss('
ul.line-legend {
    list-style: none;
    margin: 0;
    padding: 0;
}
');
?>

<div class="row traffic-consumption-chart-wrapper">
    <div class="col-md-12">
        <?php
        if ($data === []) {
            echo Yii::t('hipanel:server', 'Traffic consumption history is not available for this server');
        } else {
            echo Html::tag('div', ChartJs::widget([
                'id' => 'traffic_consumption_chart',
                'type' => 'line',
                'data' => [
                    'labels' => array_values($labels),
                    'datasets' => [
                        [
                            'label' => Yii::t('hipanel:server', 'Total outgoing traffic, Gb'),
                            'backgroundColor' => 'rgba(139, 195, 74, 0.5)',
                            'borderColor' => 'rgba(139, 195, 74, 1)',
                            'pointBackgroundColor' => 'rgba(139, 195, 74, 1)',
                            'pointBorderColor' => '#fff',
                            'data' => $data['server_traf'],
                        ],
                        [
                            'label' => Yii::t('hipanel:server', 'Total incoming traffic, Gb'),
                            'backgroundColor' => 'rgba(151,187,205,0.5)',
                            'borderColor' => 'rgba(151,187,205,1)',
                            'pointBackgroundColor' => 'rgba(151,187,205,1)',
                            'pointBorderColor' => '#fff',
                            'data' => $data['server_traf_in'],
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
