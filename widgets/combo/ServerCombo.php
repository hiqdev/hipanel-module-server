<?php

namespace hipanel\modules\server\widgets\combo;

use hipanel\widgets\Combo;
use yii\helpers\ArrayHelper;

/**
 * Class Server
 */
class ServerCombo extends Combo
{
    /** @inheritdoc */
    public $type = 'server/server';

    /** @inheritdoc */
    public $name = 'name';

    /** @inheritdoc */
    public $url = '/server/server/search';

    /** @inheritdoc */
    public $_return = ['id', 'client', 'client_id', 'seller', 'seller_id'];

    /** @inheritdoc */
    public $_rename = ['text' => 'name'];

    /** @inheritdoc */
    public $_filter = ['client' => 'client/client'];

    /** @inheritdoc */
    public $_pluginOptions = [
        'clearWhen' => ['client/client'],
        'affects'   => [
            'client/seller' => 'seller',
            'client/client' => 'client',
        ]
    ];
}