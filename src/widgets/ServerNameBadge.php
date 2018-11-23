<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\grid\ServerGridLegend;
use hipanel\modules\server\models\Server;

/**
 * Class ServerNameBadge
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ServerNameBadge extends NameBadgeInternal
{
    /**
     * @var string
     */
    public $gridLegendClass = ServerGridLegend::class;

    /** @var Server */
    public $model;

    public function run()
    {
        return parent::run();
    }
}
