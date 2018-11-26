<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\grid\ServerGridLegend;

/**
 * Class ServerNameBadge
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
