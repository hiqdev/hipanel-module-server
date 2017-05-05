<?php

/**
 * @var array
 * @var array
 * @var boolean
 * @var string
 */

use hipanel\modules\server\widgets\TrafficConsumption;

echo TrafficConsumption::widget([
    'labels' => $labels,
    'data' => $data,
    'isClientRegisterCss' => $isClientRegisterCss,
    'consumptionBase' => $consumptionBase,
]);

