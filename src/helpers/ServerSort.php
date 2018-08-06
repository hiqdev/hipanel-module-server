<?php

namespace hipanel\modules\server\helpers;

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
}
