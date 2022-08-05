<?php
declare(strict_types=1);

namespace hipanel\modules\server\widgets\combo;

use \hiqdev\combo\Combo;

class DeviceCombo extends Combo
{
    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $type = 'server/device';

    /** {@inheritdoc} */
    public $url = '/server/device/search';

    /** {@inheritdoc} */
    public $_return = ['id', 'name'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    /** {@inheritdoc} */
    public $_filter = [];

    /** {@inheritdoc} */
    public $_pluginOptions = [];

    /** {@inheritdoc} */
    protected $_primaryFilter = 'name_like';
}
