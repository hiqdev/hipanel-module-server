<?php declare(strict_types=1);

namespace hipanel\modules\server\models;

use Yii;

trait ConsumptionSearchAttributes
{
    public function consumptionSearchRules(): array
    {
        return [
            [['uses_month'], 'match', 'pattern' => '/^[A-Z][a-z]{2} \d{4}$/'],
        ];
    }

    public function consumptionAttributes(): array
    {
        return ['uses_month'];
    }

    public function consumptionAttributeLabels(): array
    {
        return [
            'uses_month' => Yii::t('hipanel:server', 'Consumption for {0}'),
        ];
    }
}
