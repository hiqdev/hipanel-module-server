<?php

namespace hipanel\modules\server\models;

class HardwareSettings extends \hipanel\base\Model
{
    const SCENARIO_DEFAULT = 'dumb';

    public static function tableName()
    {
        return 'server';
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function scenarioActions()
    {
        return [
            'default' => 'set-hardware-settings'
        ];
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['summary', 'order_no', 'brand', 'box', 'cpu', 'ram', 'motherboard', 'hdd', 'hotswap', 'raid', 'units', 'note', 'cage_no', 'rack_no', 'datacenter'], 'string'],
        ];
    }
}
