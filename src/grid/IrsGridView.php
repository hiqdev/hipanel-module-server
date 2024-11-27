<?php declare(strict_types=1);

namespace hipanel\modules\server\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\DataColumn;
use hipanel\modules\finance\models\Sale;
use hipanel\modules\server\models\Irs;
use Yii;
use yii\helpers\Html;

class IrsGridView extends ServerGridView
{
    public function columns(): array
    {
        $user = Yii::$app->user;

        return array_merge(parent::columns(), [
            'location' => [
                'class' => DataColumn::class,
                'attribute' => 'location',
                'value' => static fn($model) => $model->locationName,
                'filter' => false,
                'enableSorting' => false,
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            ],
            'server' => [
                'class' => DataColumn::class,
                'label' => Yii::t('hipanel:server', 'Server'),
                'attribute' => 'server',
                'format' => 'raw',
                'value' => static fn($model) => implode("<br>",
                    [
                        $user->can('owner-staff') ? Html::a($model->hwsummary_auto,
                            ['@server/view', 'id' => $model->id]) : $model->hwsummary_auto,
                        Html::tag('small', 'Changes are possible in the next step', ['class' => 'text-success']),
                    ]
                ),
                'filter' => false,
                'enableSorting' => false,
                'filterOptions' => ['width' => '50%'],
            ],
            'price' => [
                'class' => DataColumn::class,
                'attribute' => 'price',
                'format' => 'raw',
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'value' => function (Irs $model) use ($user): string {
                    /** @var Sale $lastSale */
                    $lastSale = $model->getActualSale();
                    if ($lastSale) {
                        $label = Yii::t('hipanel.server.irs',
                            'From {0} / Month',
                            $this->formatter->asCurrency($lastSale->fee ?? 0, $lastSale->currency));

                        return $user->can('owner-staff') ? Html::a($label, ['@plan/view', 'id' => $lastSale->tariff_id]) : $label;
                    }

                    return '';
                },
                'filter' => false,
                'enableSorting' => false,
            ],
            'ip' => [
                'class' => DataColumn::class,
                'attribute' => 'ip',
                'value' => static fn(Irs $model) => $model->getIpCount(),
                'filter' => false,
                'enableSorting' => false,
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            ],
            'os' => [
                'class' => DataColumn::class,
                'attribute' => 'os',
                'value' => static fn(Irs $model) => $model->getOsLabel(),
                'filter' => false,
                'enableSorting' => false,
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            ],
            'administration' => [
                'class' => DataColumn::class,
                'attribute' => 'administration',
                'value' => fn(Irs $model) => $model->getAdministrationLabel(),
                'filter' => false,
                'enableSorting' => false,
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            ],
            'vxlan' => [
                'class' => DataColumn::class,
                'attribute' => 'vxlan',
                'label' => Yii::t('hipanel:server:hub', 'VXLAN'),
                'value' => fn(Irs $model): string => $model->getVxlanCellValue(),
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'filter' => false,
                'enableSorting' => false,
            ],
            'delivery' => [
                'class' => DataColumn::class,
                'attribute' => 'delivery',
                'value' => static fn() => '4h',
                'filter' => false,
                'enableSorting' => false,
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'rawTemplate' => true,
                'template' => '{order}',
                'header' => false,
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'buttons' => [
                    'order' => static fn($url, $model) => Html::a(
                        Yii::t('hipanel.server.irs', 'Order'),
                        ['@irs/order', 'id' => $model->id],
                        [
                            'class' => 'btn btn-sm btn-success btn-block',
                            'style' => [
                                'text-transform' => 'uppercase',
                                'min-width' => '10rem',
                            ],
                        ],
                    ),
                ],
            ],
        ]);
    }
}
