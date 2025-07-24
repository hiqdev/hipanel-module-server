<?php

use hipanel\helpers\Url;
use hipanel\modules\server\models\Rrd;
use hipanel\modules\server\models\RrdSearch;
use hipanel\widgets\IndexPage;
use hiqdev\hiart\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var RrdSearch $searchModel
 * @var Rrd $model
 * @var ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('hipanel:server', '{server} - RRD', ['server' => $model->server->name]);
$this->params['breadcrumbs'][] = Html::a(Yii::t('hipanel:server', 'Servers'), ['@server']);
$this->params['breadcrumbs'][] = Html::a($model->server->name, ['@server/view', 'id' => $model->server->id]);
$this->params['breadcrumbs'][] = Yii::t('hipanel:server', 'RRD');

?>

<?php $page = IndexPage::begin(['model' => $searchModel, 'dataProvider' => $dataProvider, 'layout' => 'rrd']) ?>
    <?php $page->setSearchFormData(['model' => $model]) ?>
    <?php $page->setSearchFormOptions([
        'id' => 'rrd-form',
        'options' => [
            'displayNone' => false,
        ],
        'action' => ['@rrd/view', 'id' => $model->id],
        'submitButtonWrapperOptions' => [
            'class' => 'col-md-4',
        ],
    ]) ?>
    <?php $page->beginContent('table') ?>
    <?php $page->beginBulkForm() ?>
        <?= $model->isRelationPopulated('images') ? GridView::widget([
            'showHeader' => false,
            'options' => [
                'class' => 'table-responsive',
            ],
            'tableOptions' => [
                'class' => 'table',
            ],
            'summary' => false,
            'dataProvider' => new ArrayDataProvider([
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
                    },
                ],
            ],
        ]) : '' ?>
    <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
