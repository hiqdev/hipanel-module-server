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

class PrimaryServerCombo extends ServerCombo
{
    /** {@inheritdoc} */
    public $_pluginOptions = [];

    public $_filter = [
        'primary_only' => ['format' => 1],
    ];
}
