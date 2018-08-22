<?php

namespace hipanel\modules\server\models;

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

    public function rules()
    {
        return [
            [['id', 'object_id'], 'integer'],
            [['value', 'overuse'], 'safe'],
            [['type', 'limit', 'time', 'unit', 'action_unit', 'currency'], 'string'],
            [['price'], 'number'],
        ];
    }

    public function getCurrentValue()
    {
        return $this->value[$this->getCurrent()];
    }

    public function getCurrentOveruse()
    {
        return $this->overuse[$this->getCurrent()];
    }

    public function getPreviousValue()
    {
        return $this->value[$this->getPrevious()];
    }

    public function getPreviousOveruse()
    {
        return $this->overuse[$this->getPrevious()];
    }

    private function getCurrent()
    {
        return date('m');
    }

    private function getPrevious()
    {
        return date('m', strtotime('-1month'));
    }
}
