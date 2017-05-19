<?php

namespace hipanel\modules\server\models;

use hipanel\modules\server\models\query\BindingQuery;

class Binding extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public static function tableName()
    {
        return null;
    }

    public function rules()
    {
        return [
            [['device_id', 'switch_id'], 'integer'],
            [['port', 'type', 'switch', 'switch_label', 'switch_inn', 'device_ip', 'switch_ip', 'web_iface_only'], 'string'],
        ];
    }

    public static function find($options = [])
    {
        return new BindingQuery(get_called_class(), [
            'options' => $options,
        ]);
    }
}
