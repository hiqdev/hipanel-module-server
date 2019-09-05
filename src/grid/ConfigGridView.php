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
use hipanel\grid\RefColumn;
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
                    $tariff = Html::a($config->{$region . '_tariff'}, ['@plan/view', 'id' => $config->{$region . '_tariff_id'}]);
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

            $columns["{$region}_servers"] = [
                'format' => 'raw',
                'value' => function (Config $config) use ($region): string {
                    $servers = explode(',', $config->{$region . '_servers'});
                    $server_ids = explode(',', $config->{$region . '_server_ids'});

                    $links = [];
                    foreach (array_combine($server_ids, $servers) as $id => $server) {
                        $links[] = Html::a(trim($server), ['@server/view', 'id' => trim($id)]);
                    }

                    return implode(', ', array_unique($links));
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
                'filterOptions' => ['class' => 'narrow-filter'],
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
                    foreach (['cpu', 'ram', 'hdd', 'ssd'] as $item) {
                        if (empty($model->$item)) {
                            continue;
                        }
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
                'value' => function (Config $config): string {
                    $colors = ['bg-teal', 'bg-green', 'bg-yellow', 'bg-purple', 'bg-aqua', 'bg-red'];
                    return Html::tag('ul', implode('<br>', array_map(function ($profile) use (&$colors) {
                        return Html::tag('li', $profile, ['class' => 'badge ' . array_pop($colors)]);
                    }, array_map('trim', explode(',', $config->profiles)))), ['class' => 'list-unstyled']);
                },
            ],
            'tariffs' => [
                'class' => MainColumn::class,
                'format' => 'html',
                'value' => function ($model) {
                    return Html::tag('span', 'NL:') .
                        Html::a($model->nl_tariff, ['@plan/view', 'id' => $model->nl_tariff_id]) . '</br>' .
                        Html::tag('span', 'US:') .
                        Html::a($model->us_tariff, ['@plan/view', 'id' => $model->us_tariff_id]);
                },
            ],
            'servers' => [
                'format' => 'raw',
                'value' => function (Config $model) {
                    $servers = explode(',', $model->servers);
                    $server_ids = explode(',', $model->server_ids);

                    $links = [];
                    foreach (array_combine($server_ids, $servers) as $id => $server) {
                        $links[] = Html::a(trim($server), ['@server/view', 'id' => trim($id)]);
                    }

                    return implode(', ', array_unique($links));
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
            'state' => [
                'label' => Yii::t('hipanel', 'State'),
                'format' => 'raw',
                'class' => RefColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
                'i18nDictionary' => 'hipanel',
                'gtype' => 'state,config',
            ],
        ]);
    }
}
