<?php

namespace hipanel\modules\server\cart;

class ServerRenewPurchase extends AbstractServerPurchase
{
    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Renew';
    }

    /**
     * @var string domain expiration datetime
     */
    public $expires;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['expires'], 'required'],
        ]);
    }
}
