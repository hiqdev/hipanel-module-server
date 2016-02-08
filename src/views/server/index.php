<?php

use hipanel\base\View;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\widgets\ActionBox;
use hipanel\widgets\BulkButtons;
use hipanel\widgets\LinkSorter;
use hipanel\widgets\Pjax;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;

/**
 * @var OsimageSearch $osimages
 */

/**
 * @var View $this
 */
$this->title = Yii::t('hipanel/server', 'Servers');
$this->subtitle = Yii::t('app', array_filter(Yii::$app->request->get($model->formName(), [])) ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title
]);

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true]));
?>

<?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider, 'bulk' => false]) ?>
<?php $box->beginActions() ?>
<?= $box->renderSearchButton() ?>
<?= $box->renderSorter([
    'attributes' => [
        'name',
        'id',
        'client',
        'tariff',
        'ip',
        'state',
        'expires',
    ],
]) ?>
<?= $box->renderPerPage() ?>
<?php $box->endActions() ?>
<?= $box->renderSearchForm(compact('states')) ?>
<?php $box->end() ?>

<?php
$box->beginBulkForm();
print ServerGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'osImages' => $osimages,
    'columns' => [
        'checkbox',
        'server',
        'client_id',
        'seller_id',
        'ips',
        'state',
        'expires',
        'tariff_and_discount',
        'actions',
    ]
]);
$box->endBulkForm();
Pjax::end();
