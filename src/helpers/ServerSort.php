<?php

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
            'overuse,server_traf_max',
            'overuse,ip_num',
            'overuse,backup_du',
            'overuse,support_time',
        ];

        return Sort::chain()->asc(function (Consumption $consumption) use ($order) {
            if (($key = array_search($consumption->type, $order)) !== false) {
                return $key;
            }

            return INF;
        });
    }
}
