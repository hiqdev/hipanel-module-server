<?php

namespace hipanel\modules\server\models;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\finance\providers\BillTypesProvider;
use hipanel\modules\server\helpers\consumptionPriceFormatter\MultiplePrice;
use hipanel\modules\server\helpers\consumptionPriceFormatter\PriceFormatterStrategy;
use hipanel\modules\server\helpers\consumptionPriceFormatter\SinglePrice;
use Yii;

/**
 * Class Consumption
 *
 * @property string $type
 * @property float[] $value
 * @property float[] $overuse
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
            [['value', 'overuse', 'prices'], 'safe'],
            [['type', 'limit', 'time', 'unit', 'action_unit', 'currency'], 'string'],
            [['price'], 'number'],
        ];
    }

    /**
     * Get type label
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTypeLabel(): string
    {
        $provider = Yii::createObject(BillTypesProvider::class);
        $types = ArrayHelper::index($provider->getTypes(), 'name');
        if (!isset($types[$this->type])) {
            return '--';
        }

        return $types[$this->type]->label;
    }

    public function getCurrentValue(): ?string
    {
        return $this->value[$this->getCurrent()];
    }

    public function getCurrentOveruse(): ?string
    {
        return $this->overuse[$this->getCurrent()];
    }

    public function getPreviousValue(): ?string
    {
        return $this->value[$this->getPrevious()];
    }

    public function getPreviousOveruse(): ?string
    {
        return $this->overuse[$this->getPrevious()];
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
