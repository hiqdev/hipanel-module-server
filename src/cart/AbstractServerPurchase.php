<?php

namespace hipanel\modules\server\cart;

/**
 * Abstract class AbstractServerPurchase.
 */
abstract class AbstractServerPurchase extends \hipanel\modules\finance\cart\AbstractPurchase
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public static function type()
    {
        return 'server';
    }

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->server = $this->position->name;
        $this->amount = $this->position->getQuantity();
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['server', 'expires'], 'safe'],
            [['amount'], 'number'],
        ]);
    }
}
