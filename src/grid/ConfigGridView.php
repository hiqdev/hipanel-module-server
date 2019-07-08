<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\MainColumn;
use hipanel\modules\server\menus\ConfigActionsMenu;
use hipanel\modules\server\models\Config;
use hipanel\modules\server\models\ConfigSearch;
use hipanel\modules\server\widgets\combo\ConfigProfileCombo;
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\helpers\Html;

class ConfigGridView extends BoxedGridView
{
    /**
     * @return array
     */
    private function getTariffsWithOldPriceColumns(): array
    {
        $columns = [];
        foreach (['nl' => 'EUR', 'us' => 'USD'] as $region => $currency) {
            $columns["{$region}_tariff"] = [
                'format' => 'raw',
                'value' => function (Config $config) use ($region, $currency): string {
                    $tariff = Html::tag('span', $config->{$region . '_tariff'});
                    $oldPrice = Html::tag(
                        'span',
                        Yii::t('hipanel:server:config', 'Old price: {price}', [
                            'price' => Yii::$app->formatter->asCurrency($config->{$region . '_old_price'}, $currency)
                        ]),
                        ['class' => 'badge']
                    );

                    return Html::tag('span', $tariff . $oldPrice, [
                        'style' => 'display: flex; flex-direction: row; justify-content: space-between; flex-wrap: wrap;'
                    ]);
                },
            ];
        }

        return $columns;
    }

    public function columns()
    {
        return array_merge(parent::columns(), $this->getTariffsWithOldPriceColumns(), [
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => ConfigActionsMenu::class,
            ],
            'name' => [
                'class' => MainColumn::class,
                'label' => Yii::t('hipanel', 'Name'),
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a($model->name, ['@config/view', 'id' => $model->id]) .
                        '</br>' . Html::tag('span', $model->label);
                },
            ],
            'config' => [
                'class' => MainColumn::class,
                'format' => 'html',
                'value' => function ($model) {
                    $value = '';
                    foreach (['cpu', 'ram', 'hdd'] as $item) {
                        $value .= '<nobr>' . Html::tag('span', strtoupper($item) . ': ') .
                            Html::tag('span', $model->$item) . '</nobr></br>';
                    }

                    return $value;
                },
            ],
            'profiles' => [
                'format' => 'raw',
                'filterOptions' => ['class' => 'narrow-filter'],
                'attribute' => 'profiles',
                'filter' => ConfigProfileCombo::widget([
                    'attribute' => 'profiles_like',
                    'model' => $this->filterModel ?? new ConfigSearch(),
                    'formElementSelector' => 'td',
                ]),
                'enableSorting' => false,
                'value' => function (Config $config) {
                    $colors = ['bg-teal', 'bg-green', 'bg-yellow', 'bg-purple', 'bg-aqua', 'bg-red'];
                    return Html::tag('ul', implode('<br>', array_map(function ($profile) use ($colors) {
                        return Html::tag('li', $profile, ['class' => 'badge ' . $colors[rand(0, 5)]]);
                    }, array_map('trim', explode(',', $config->profiles)))), ['class' => 'list-unstyled']);
                },
            ],
            'tariffs' => [
                'class' => MainColumn::class,
                'format' => 'html',
                'value' => function ($model) {
                    return Html::tag('span', 'NL:') .
                        Html::a($model->nl_tariff, ['@tariff/view', 'id' => $model->nl_tariff_id]) . '</br>' .
                        Html::tag('span', 'US:') .
                        Html::a($model->us_tariff, ['@tariff/view', 'id' => $model->us_tariff_id]);
                },
            ],
            'servers' => [
                'value' => function (Config $model) {
                    return implode(', ', array_unique(explode(', ', $model->servers)));
                },
            ],
            'cc_servers' => [
                'label' => Yii::t('hipanel', 'Servers'),
                'format' => 'html',
                'value' => function ($model) {
                    return Html::tag('span', 'NL:') . $model->nl_servers . '<br/>' .
                        Html::tag('span', 'US:') . $model->us_servers;
                },
            ],
        ]);
    }
}
