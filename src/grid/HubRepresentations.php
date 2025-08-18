<?php
declare(strict_types=1);

namespace hipanel\modules\server\grid;

use hipanel\modules\finance\module\ConsumptionConfiguration\Application\ConsumptionConfigurator;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class HubRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $user = Yii::$app->user;
        $consumptionConfigurator = Yii::$container->get(ConsumptionConfigurator::class);
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'switch',
                    'inn',
                    'model',
                    'type',
                    'server_type',
                    'state_label',
                    'ip',
                    'mac',
                    'order_no',
                ],
            ],
            'sale' => [
                'label' => Yii::t('hipanel:server:hub', 'sale'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'switch',
                    'buyer',
                    'tariff',
                    'model',
                    'type',
                ],
            ],
            'consumption' => $user->can('consumption.read') ? [
                'label' => Yii::t('hipanel:server', 'consumption'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'switch',
                    'type',
                    'server_type',
                    ...$consumptionConfigurator->getColumns('switch'),
                ],
            ] : null,
            'manager' => [
                'label' => Yii::t('hipanel', 'manager'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'rack',
                    'switch',
                    'model',
                    'inn',
                    'client',
                    'tariff',
                ],
            ],
        ]);
    }
}
