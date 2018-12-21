<?php

namespace hipanel\modules\server\helpers\consumptionPriceFormatter;


use hipanel\modules\server\models\Consumption;
use Yii;
use yii\helpers\Html;

class MultiplePrice implements PriceFormatterStrategy
{
    /**
     * @var string
     */
    private $delimiter;

    /**
     * @param string $delimiter
     */
    public function __construct(?string $delimiter = null)
    {
        $this->delimiter = $delimiter ?? Html::tag('br');
    }

    /**
     * @param Consumption $consumption
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function showPrice(Consumption $consumption): string
    {
        $prices = [];
        foreach ($consumption->prices as $currency => $amount) {
            $prices[] = Yii::$app->formatter->asCurrency($amount, $currency);
        }

        return implode($this->delimiter, $prices);
    }
}
