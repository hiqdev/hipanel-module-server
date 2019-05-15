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

class SinglePrice implements PriceFormatterStrategy
{
    /**
     * @param Consumption $consumption
     * @throws \yii\base\InvalidConfigException
     * @return string
     */
    public function showPrice(Consumption $consumption): string
    {
        return $consumption->price ? Yii::$app->formatter->asCurrency($consumption->price, $consumption->currency) : '';
    }
}
