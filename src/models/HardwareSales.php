<?php

namespace hipanel\modules\server\models;

class HardwareSales extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['id', 'tariff_id', 'price_id', 'part_id'], 'integer'],
            [['scenario', 'sale_time', 'part'], 'string'],
            ['data', 'safe'],
        ];
    }
}
