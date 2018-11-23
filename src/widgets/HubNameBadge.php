<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\forms\HubSellForm;
use hipanel\modules\server\grid\HubGridLegend;
use hipanel\modules\server\models\Hub;

class HubNameBadge extends NameBadgeInternal
{
    /**
     * @var string
     */
    public $gridLegendClass = HubGridLegend::class;

    /** @var HubSellForm|Hub */
    public $model;

    public function run()
    {
        return parent::run();
    }
}
