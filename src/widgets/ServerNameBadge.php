<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\grid\ServerGridLegend;
use hipanel\modules\server\models\Server;
use hipanel\widgets\gridLegend\GridLegend;
use yii\base\Widget;

/**
 * Class ServerNameBadge
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ServerNameBadge extends Widget
{
    /** @var Server */
    public $model;

    public function run()
    {
        $color = GridLegend::create(new ServerGridLegend($this->model))->gridColumnOptions('actions');
        $colorString = implode(';', $color);

        $this->view->registerCss('.badge .server-badge-text { color: #bbbbbb; mix-blend-mode: difference; }');

        return <<<HTML
<span class="badge" style="$colorString"><span class="server-badge-text">{$this->model->name}</span></span>
HTML
            ;
    }
}
