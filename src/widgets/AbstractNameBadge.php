<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\forms\HubSellForm;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\models\Server;
use hipanel\widgets\gridLegend\GridLegend;
use yii\base\Widget;

abstract class AbstractNameBadge extends Widget
{
    /**
     * @var string
     */
    public $gridLegendClass;

    /**
     * @var string
     */
    public $nameAttribute = 'name';

    /**
     * @var Hub|Server|HubSellForm
     */
    public $model;

    public function run()
    {
        $color = GridLegend::create(new $this->gridLegendClass($this->model))->gridColumnOptions('actions');
        $colorString = implode(';', $color);

        $this->view->registerCss('.badge .item-badge-text { color: #bbbbbb; mix-blend-mode: difference; }');

        return <<<HTML
<span class="badge" style="$colorString"><span class="item-badge-text">{$this->model->{$this->nameAttribute}}</span></span>
HTML
            ;
    }
}

