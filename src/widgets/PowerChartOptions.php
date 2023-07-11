<?php
declare(strict_types=1);

namespace hipanel\modules\server\widgets;

use Yii;
use yii\helpers\Html;

class PowerChartOptions extends ChartOptions
{
//    public $pickerOptions = [
//
//    ];

    protected function buildAggregationSelect(): string
    {
        return Html::dropDownList('aggregation', 'month', [
            'month' => Yii::t('hipanel', 'Monthly'),
        ], ['class' => 'form-control input-sm']);
    }
}
