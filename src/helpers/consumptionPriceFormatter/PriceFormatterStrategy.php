<?php

namespace hipanel\modules\server\helpers\consumptionPriceFormatter;

use hipanel\modules\server\models\Consumption;

interface PriceFormatterStrategy
{
    /**
     * @param Consumption $consumption
     * @return string
     */
    public function showPrice(Consumption $consumption): string;
}

