<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\finance\providers\BillTypesProvider;
use hipanel\modules\server\helpers\consumptionPriceFormatter\MultiplePrice;
use hipanel\modules\server\helpers\consumptionPriceFormatter\PriceFormatterStrategy;
use hipanel\modules\server\helpers\consumptionPriceFormatter\SinglePrice;
use Yii;

/**
 * Class Consumption.
 *
 * @property int $id
 * @property int $object_id
 * @property float[] $value
 * @property float[] $overuse
 * @property float[] $prices
 * @property string $type
 * @property string $limit
 * @property string $time
 * @property string $unit
 * @property string $action_unit
 * @property string $currency
 * @property float $price
 */
class Consumption extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    /**
     * @var PriceFormatterStrategy
     */
    private $priceFormatterStrategy;

    public function rules()
    {
        return [
            [['id', 'object_id'], 'integer'],
            [['value', 'overuse', 'prices', 'tariff_price'], 'safe'],
            [['type', 'limit', 'time', 'unit', 'action_unit', 'currency'], 'string'],
            [['price'], 'number'],
        ];
    }

    /**
     * Get type label.
     * @throws \yii\base\InvalidConfigException
     * @return string
     */
    public function getTypeLabel(): string
    {
        $provider = Yii::createObject(BillTypesProvider::class);
        $provider->keepUnusedTypes();
        $types = ArrayHelper::index($provider->getTypes(), 'name');
        if (!isset($types[$this->type])) {
            return '--';
        }

        return $types[$this->type]->label;
    }

    public function getCurrentValue(): ?string
    {
        return $this->value[$this->getCurrent()] ?? null;
    }

    public function getCurrentOveruse(): ?string
    {
        return $this->overuse[$this->getCurrent()] ?? null;
    }

    public function getPreviousValue(): ?string
    {
        return $this->value[$this->getPrevious()] ?? null;
    }

    public function getPreviousOveruse(): ?string
    {
        return $this->overuse[$this->getPrevious()] ?? null;
    }

    private function getCurrent(): string
    {
        return date('m');
    }

    private function getPrevious(): string
    {
        return date('m', strtotime('-1month'));
    }

    public function getFormattedPrice(): string
    {
        if ($this->prices) {
            $this->setPriceFormatterStrategy(new MultiplePrice());
        } else {
            $this->setPriceFormatterStrategy(new SinglePrice());
        }

        return $this->priceFormatterStrategy->showPrice($this);
    }

    public function hasFormattedAttributes(): bool
    {
        return $this->limit || ($this->price || $this->prices);
    }

    /**
     * @param PriceFormatterStrategy $priceFormatterStrategy
     * @return Consumption
     */
    private function setPriceFormatterStrategy(PriceFormatterStrategy $priceFormatterStrategy): Consumption
    {
        $this->priceFormatterStrategy = $priceFormatterStrategy;

        return $this;
    }
}
