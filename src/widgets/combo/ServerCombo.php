<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets\combo;

use hiqdev\combo\Combo;

/**
 * Class Server.
 */
class ServerCombo extends Combo
{
    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $type = 'server/server';

    /** {@inheritdoc} */
    public $url = '/server/server/search';

    /** {@inheritdoc} */
    public $_return = ['id', 'client', 'client_id', 'seller', 'seller_id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    /** {@inheritdoc} */
    public $_filter = ['client' => 'client/client'];

    /** {@inheritdoc} */
    public $_pluginOptions = [
        'clearWhen' => ['client/client'],
        'affects'   => [
            'client/seller' => 'seller',
            'client/client' => 'client',
        ],
    ];
}
