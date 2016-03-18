<?php

namespace hipanel\modules\server\cart;

class Calculation extends \hipanel\modules\finance\models\Calculation
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->client = $this->position->getModel()->client;
        $this->seller = $this->position->getModel()->seller;
        $this->object = 'server';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['server', 'expires'], 'safe'],
            [['id'], 'integer'],
        ]);
    }
}
