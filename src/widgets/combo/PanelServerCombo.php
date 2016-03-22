<?php

namespace hipanel\modules\server\widgets\combo;

use hiqdev\combo\Combo;

/**
 * Class Server
 */
class PanelServerCombo extends ServerCombo
{
    public $_filter = [
        'client' => 'client/client',
        'panel' => ['format' => 'rcp']
    ];
}
