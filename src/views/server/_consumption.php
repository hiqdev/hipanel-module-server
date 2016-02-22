<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;

$labels = [];
$sets = [];

$usesPerMonth = (array)$model->uses;

?>

<div class="row">
    <div class="col-md-12">
        <?php

        if ($usesPerMonth === []) {
            echo Yii::t('hipanel/server', 'Traffic consumption history is not available for this server');
        } else {
            ksort($usesPerMonth);

            foreach ($usesPerMonth as $month => $uses) {
                $labels[] = Yii::$app->formatter->asDate(strtotime($month), 'LLL y');

                foreach ($uses as $use) {
                    if (in_array($use['type'], ['server_traf95', 'server_traf95_in', 'server_traf95_max'])) {
                        $value = $use['last'];
                    } else {
                        $value = $use['total'];
                    }

                    $data[$use['type']][] = $value;
                }
            }

            echo Html::tag('div', ChartJs::widget([
                'type' => 'Line',
                'data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'out',
                            'fillColor' => "rgba(220,220,220,0.5)",
                            'strokeColor' => "rgba(220,220,220,1)",
                            'pointColor' => "rgba(220,220,220,1)",
                            'pointStrokeColor' => "#fff",
                            'data' => $data['server_traf']
                        ],
                        [
                            'label' => 'out',
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
