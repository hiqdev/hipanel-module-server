<?php

namespace hipanel\modules\server\models;

class Binding extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['device_id', 'switch_id'], 'integer'],
            [['port', 'type', 'switch', 'switch_label', 'switch_inn', 'device_ip', 'switch_ip', 'web_iface_only'], 'string'],
        ];
    }
}
