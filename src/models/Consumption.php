<?php

namespace hipanel\modules\server\models;

use hipanel\modules\finance\logic\bill\BillQuantityFactory;
use hipanel\modules\finance\models\Bill;
use hipanel\modules\finance\models\Price;
use Yii;

class Consumption extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['id', 'object_id'], 'integer'],
            [['value', 'overuse'], 'safe'],
            [['type', 'limit', 'time', 'unit', 'action_unit', 'currency'], 'string'],
            [['price'], 'number'],
        ];
    }

    public function getPriceWithCurrency(): string
    {
        return Yii::$app->formatter->asCurrency($this->price, $this->currency);
    }

    public function getCurrentValue()
    {
        return $this->getText($this->value[$this->getCurrent()]);
    }

    public function getCurrentOveruse()
    {
        return $this->getText($this->overuse[$this->getCurrent()]);
    }

    public function getPreviousValue()
    {
        return $this->getText($this->value[$this->getPrevious()]);
    }

    public function getPreviousOveruse()
    {
        return $this->getText($this->overuse[$this->getPrevious()]);
    }

    public function getLimitText()
    {
        return $this->getText($this->limit);
    }

    private function getCurrent()
    {
        return date('m');
    }

    private function getPrevious()
    {
        return date('m', strtotime('-1month'));
    }

    private function getText($value): string
    {
//        $price = new Price(['type' => $this->type, 'unit' => $this->unit]);
        $bill = new Bill(['type' => $this->type, 'quantity' => $value]);
        return Yii::t('hipanel:server', '{amount}', [
            'amount' => Yii::$container->get(BillQuantityFactory::class)->createByType($this->type, $bill)->getText(),
//            'unit' => $price->getUnitLabel(),
        ]);
    }
}
