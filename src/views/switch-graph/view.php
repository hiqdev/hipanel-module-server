<?php

use hipanel\base\View;
use hipanel\helpers\Url;
use hipanel\widgets\ActionBox;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var View $this
 * @var \hipanel\modules\server\models\SwitchGraphSearch $model
 * @var \hiqdev\hiart\ActiveDataProvider $dataProvider
 */

$searchModel = $model;
$models = $dataProvider->getModels();
$model = reset($models);

$this->title = Yii::t('hipanel/server', '{server} - Switch graphs', ['server' => $model->server->name]);
$this->params['breadcrumbs'][] = Html::a(Yii::t('hipanel/server', 'Servers'), ['@server']);
$this->params['breadcrumbs'][] = Html::a($model->server->name, ['@server/view', 'id' => $model->server->id]);
$this->params['breadcrumbs'][] = Yii::t('hipanel/server', 'Switch graphs');

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(['model' => $searchModel, 'dataProvider' => $dataProvider, 'layout' => 'rrd']) ?>
        <?= $page->setSearchFormData(['model' => $model]) ?>
        <?= $page->setSearchFormOptions([
            'id' => 'switchgraph-form',
            'options' => [
                'displayNone' => false,
            ],
            'action' => ['@switch-graph/view', 'id' => $model->id],
            'submitButtonWrapperOptions' => [
                'class' => 'col-md-2 md-pt-20'
            ],
        ]) ?>
        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= GridView::widget([
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
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
