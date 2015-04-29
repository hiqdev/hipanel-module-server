<?php

namespace hipanel\modules\server\assets\combo2;

use hipanel\widgets\Combo2Config;
use yii\helpers\ArrayHelper;

/**
 * Class Server
 */
class Server extends Combo2Config
{
    /** @inheritdoc */
    public $type = 'server';

    /** @inheritdoc */
    public $_primaryFilter = 'name_like';

    /** @inheritdoc */
    public $url = '/server/server/search';

    /** @inheritdoc */
    public $_return = ['id', 'client', 'client_id', 'seller', 'seller_id'];

    /** @inheritdoc */
    public $_rename = ['text' => 'name'];

    /** @inheritdoc */
    public $_filter = ['client' => 'client'];

    /** @inheritdoc */
    function getConfig ($config = []) {
        $config = ArrayHelper::merge([
            'clearWhen' => ['client'],
            'affects'   => [
                'client' => 'client',
                'seller' => 'seller'
            ]
        ], $config);

        return parent::getConfig($config);
    }
}