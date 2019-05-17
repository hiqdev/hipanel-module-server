<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\helpers;

use hipanel\modules\server\models\Consumption;
use hipanel\modules\server\models\Server;
use Tuck\Sort\Sort;
use Tuck\Sort\SortChain;

class ServerSort
{
    public static function byServerName(): SortChain
    {
        return Sort::chain()->compare(function (Server $a, Server $b) {
            return strnatcasecmp($a->name, $b->name);
        });
    }

    public static function byConsumptionType(): SortChain
    {
        $order = [
            'monthly,monthly',
            'monthly,rack',
            'monthly,rack_unit',
            'monthly,hardware',
            'monthly,server_traf_max',
            'monthly,server_traf95_max',
            'overuse,server_traf_max',
            'overuse,server_traf95_max',
            'monthly,support_time',
            'overuse,support_time',
            'monthly,backup_du',
            'overuse,backup_du',
            'monthly,ip_num',
            'overuse,ip_num',
        ];

        return Sort::chain()->asc(function (Consumption $consumption) use ($order) {
            if (($key = array_search($consumption->type, $order, true)) !== false) {
                return $key;
            }

            return INF;
        });
    }
}
