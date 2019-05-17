<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\grid\ServerGridLegend;

/**
 * Class ServerNameBadge.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ServerNameBadge extends AbstractNameBadge
{
    /**
     * @var string
     */
    public $gridLegendClass = ServerGridLegend::class;
}
