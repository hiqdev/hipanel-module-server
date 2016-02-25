<?php

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
