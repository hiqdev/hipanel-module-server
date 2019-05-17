<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets\combo;

/**
 * Class Server.
 */
class PanelServerCombo extends ServerCombo
{
    public $state;

    public function init()
    {
        parent::init();

        if ($this->state !== null) {
            $this->_filter['state'] = ['format' => $this->state];
        }
    }

    public $_filter = [
        'client' => 'client/client',
        'panel' => ['format' => 'rcp'],
    ];
}
