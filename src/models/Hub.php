<?php

namespace hipanel\modules\server\models;

class Hub extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'access_id', 'type_id', 'state_id', 'buyer_id', 'units'], 'integer'],
            [['name', 'dc', 'mac', 'remoteid', 'note', 'ip', 'type_label', 'buyer', 'note', 'inn', 'model',
                'community', 'login', 'traf_server_id', 'order_no'], 'string'],
            [['virtual'], 'boolean'],

            [['type_id', 'name'], 'required', 'on' => ['create', 'update']],
        ]);
    }
}
