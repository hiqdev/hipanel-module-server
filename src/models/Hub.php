<?php

namespace hipanel\modules\server\models;

class Hub extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'access_id', 'type_id', 'state_id'], 'integer'],
            [['name', 'dc', 'mac', 'remoteid', 'note', 'ip', 'type_label'], 'string'],
            [['virtual'], 'boolean'],
        ]);
    }
}
