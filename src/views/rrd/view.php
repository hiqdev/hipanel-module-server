<?php

use hipanel\base\View;
use hipanel\helpers\Url;
use hipanel\widgets\ActionBox;
use hipanel\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var View $this
 * @var \hipanel\modules\server\models\RrdSearch $model
 * @var \hiqdev\hiart\ActiveDataProvider $dataProvider
 */

$searchModel = $model;
$models = $dataProvider->getModels();
$model = reset($models);

$this->title = Yii::t('hipanel/server', '{server} - RRD', ['server' => $model->server->name]);
$this->breadcrumbs->setItems([
    Html::a(Yii::t('hipanel/server', 'Servers'), ['@server']),
    Html::a($model->server->name, ['@server/view', 'id' => $model->server->id]),
    Yii::t('hipanel/server', 'RRD')
]);

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true]));

$box = ActionBox::begin(['model' => $searchModel, 'dataProvider' => $dataProvider, 'bulk' => false]);
    echo $box->renderSearchForm(['model' => $model], [
        'id' => 'rrd-form',
        'options' => [
            'displayNone' => false,
        ],
        'action' => ['@rrd/view', 'id' => $model->id]
    ]);
$box->end();

echo GridView::widget([
    'showHeader' => false,
    'options' => [
        'class' => 'table-responsive'
    ],
    'tableOptions' => [
        'class' => 'table',
    ],
    'summary' => false,
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels' => $model->images,
        'pagination' => false,
        'sort' => false,
    ]),
    'columns' => [
        [
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
                $html = Html::tag('img', '', ['src' => 'data:image/png;base64,' . $model->base64]);

                if ($model->graph) {
                    $html = Html::a($html, Url::current(['graph' => $model->graph]));
                }

                return Html::tag('div', $html, ['class' => 'text-center']);
            }
        ]
    ]
]);
Pjax::end();
