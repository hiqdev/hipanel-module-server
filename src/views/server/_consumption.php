<?php

/**
 * @var array $labels
 * @var array $data
 * @var string $consumptionBase
 */

use hipanel\modules\server\widgets\ResourceConsumption;
use hipanel\modules\server\widgets\TrafficConsumption;

$options = [
    'id' => 'widget_id_tc_' . $consumptionBase,
    'labels' => $labels,
    'data' => $data,
    'consumptionBase' => $consumptionBase,
];
echo in_array($consumptionBase, ['server_traf', 'server_traf95']) ? TrafficConsumption::widget($options) : ResourceConsumption::widget($options);

