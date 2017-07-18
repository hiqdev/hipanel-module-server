<?php

/**
 * @var array $labels
 * @var array $data
 * @var string $consumptionBase
 */

use hipanel\modules\server\widgets\TrafficConsumption;

echo TrafficConsumption::widget([
    'labels' => $labels,
    'data' => $data,
    'consumptionBase' => $consumptionBase,
]);

