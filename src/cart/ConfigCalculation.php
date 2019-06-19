<?php

namespace hipanel\modules\server\cart;

use hipanel\base\ModelTrait;
use hipanel\modules\finance\cart\Calculation;


class ConfigCalculation extends Calculation
{
    use ModelTrait;

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->object = 'serverConfig';
        $this->type = 'estimate';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['object_id'], 'integer'],
            [['location'], 'string'],
        ]);
    }
}
