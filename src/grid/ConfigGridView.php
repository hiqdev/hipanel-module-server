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
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\helpers\Html;

class ConfigGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
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
                        $value .= '<nobr>' . Html::tag('span', ucwords($item) . ': ') .
                            Html::tag('span', $model->$item) . '</nobr></br>';
                    }

                    return $value;
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
                'format' => 'html',
                'value' => function ($model) {
                    return  Html::tag('span', 'NL:') . $model->nl_servers . '<br/>' .
                            Html::tag('span', 'US:') . $model->us_servers;
                },
            ],
        ]);
    }
}
