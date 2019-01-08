<?php

namespace hipanel\modules\server\helpers\consumptionPriceFormatter;


use hipanel\modules\server\models\Consumption;
use Yii;

class SinglePrice implements PriceFormatterStrategy
{
    /**
     * @param Consumption $consumption
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function showPrice(Consumption $consumption): string
    {
        return $consumption->price ? Yii::$app->formatter->asCurrency($consumption->price, $consumption->currency) : '';
    }
}
