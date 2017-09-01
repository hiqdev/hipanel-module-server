<?php

/**
 * @var array $labels
 * @var array $data
 * @var string $consumptionBase
 */

use hipanel\modules\server\widgets\TrafficConsumption;

echo TrafficConsumption::widget([
    'id' => 'widget_id_tc_' . $consumptionBase,
    'labels' => $labels,
    'data' => $data,
    'consumptionBase' => $consumptionBase,
]);

