<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

use hipanel\modules\server\widgets\ResourceConsumption;
use hipanel\modules\server\widgets\TrafficConsumption;

/** @var string $consumptionBase */
/** @var array $labels */
/** @var array $data */

$options = [
    'id' => 'widget_id_tc_' . $consumptionBase,
    'labels' => $labels,
    'data' => $data,
    'consumptionBase' => $consumptionBase,
];

if (in_array($consumptionBase, ['server_traf', 'server_traf95'], true)) {
    echo TrafficConsumption::widget($options);
} else {
    echo ResourceConsumption::widget($options);
}
