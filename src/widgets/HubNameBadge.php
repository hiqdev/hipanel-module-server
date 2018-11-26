<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\grid\HubGridLegend;

class HubNameBadge extends AbstractNameBadge
{
    /**
     * @var string
     */
    public $gridLegendClass = HubGridLegend::class;
}
