<?php

namespace hipanel\modules\server\models;

class HardwareSettings extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['summary', 'order_no', 'brand', 'box', 'cpu', 'ram', 'motherboard', 'hdd', 'hotswap', 'raid', 'units', 'note', 'cage_no', 'rack_no', 'datacenter'], 'string'],
        ];
    }
}
