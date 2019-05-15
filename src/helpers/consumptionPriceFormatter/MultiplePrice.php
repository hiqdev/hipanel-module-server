<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

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
     * @throws \yii\base\InvalidConfigException
     * @return string
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
