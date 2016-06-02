<?php

use hipanel\base\View;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\widgets\IndexLayoutSwitcher;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;

/**
 * @var OsimageSearch $osimages
 */

/**
 * @var View $this
 * @var array $states
 */

$this->title = Yii::t('hipanel/server', 'Pending confirmation servers');
$this->subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->breadcrumbs->setItems([$this->title]); ?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])); ?>
<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
<?= $page->setSearchFormData(compact(['states'])) ?>
<?php $page->beginContent('main-actions') ?>

<?php $page->endContent() ?>
<?php $page->beginContent('show-actions') ?>
<?= IndexLayoutSwitcher::widget() ?>
<?= $page->renderSorter([
    'attributes' => [
        'client',
        'user_comment',
        'tech_comment',
    ],
]) ?>

<?= $page->renderPerPage() ?>
<?= $page->renderRepresentation() ?>
<?php $page->endContent() ?>

<?php $page->beginContent('table') ?>
<?php $page->beginBulkForm(); ?>
<?= \hipanel\modules\finance\grid\ChangeGridView::widget([
    'dataProvider' => $dataProvider,
    'boxed' => false,
    'filterModel' => $model,
    'columns' => [
        'client',
        'user_comment',
        'tech_comment',
        'actions',
    ]
]); ?>
<?php $page->endBulkForm(); ?>
<?php $page->endContent() ?>
<?php $page->end() ?>

<?php Pjax::end(); ?>
