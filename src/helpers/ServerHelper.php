<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\helpers;

use hipanel\modules\server\models\ServerUse;
use yii\helpers\ArrayHelper;

class ServerHelper
{
    public static function groupUsesForChart($uses)
    {
        $labels = [];
        $data = [];

        ArrayHelper::multisort($uses, 'date');

        foreach ($uses as $use) {
            /** @var ServerUse $use */
            $labels[$use->date] = $use;
            $data[$use->type][] = $use->getDisplayAmount();
        }

        foreach ($labels as $date => $use) {
            $labels[$date] = $use->getDisplayDate();
        }

        return [$labels, $data];
    }
}
