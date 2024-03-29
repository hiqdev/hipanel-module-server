<?php

use hipanel\modules\server\grid\RefuseGridView;
use hipanel\modules\server\models\Change;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\IndexPage;
use hiqdev\hiart\ActiveDataProvider;
use yii\helpers\Html;

/**
 * @var OsimageSearch
 * @var yii\web\View $this
 * @var array $states
 * @var Change $model
 * @var ActiveDataProvider $dataProvider
 */
$this->title = Yii::t('hipanel:server', 'Refuses');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = Html::a(Yii::t('hipanel:server', 'Servers'), ['@server']);
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
    <?php $page->setSearchFormData(['states' => $states]) ?>
    <?php $page->beginContent('main-actions') ?>
        <?php // TODO: add actions?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'client',
                'time',
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if ($model->state === $model::STATE_NEW) : ?>
            <div>
                <?= AjaxModal::widget([
                    'id' => 'bulk-approve-modal',
                    'bulkPage' => true,
                    'header'=> Html::tag('h4', Yii::t('hipanel:finance:change', 'Approve'), ['class' => 'modal-title']),
                    'scenario' => 'bulk-approve',
                    'actionUrl' => ['bulk-approve-modal'],
                    'size' => AjaxModal::SIZE_LARGE,
                    'handleSubmit' => false,
                    'toggleButton' => [
                        'class' => 'btn btn-success btn-sm',
                        'label' => Yii::t('hipanel:finance:change', 'Approve'),
                    ],
                ]) ?>
                <?= AjaxModal::widget([
                    'id' => 'bulk-reject-modal',
                    'bulkPage' => true,
                    'header'=> Html::tag('h4', Yii::t('hipanel:finance:change', 'Reject'), ['class' => 'modal-title ']),
                    'scenario' => 'reject',
                    'actionUrl' => ['bulk-reject-modal'],
                    'size' => AjaxModal::SIZE_LARGE,
                    'handleSubmit' => false,
                    'toggleButton' => [
                        'class' => 'btn btn-danger btn-sm',
                        'label' => Yii::t('hipanel:finance:change', 'Reject'),
                    ],
                ]) ?>
            </div>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= RefuseGridView::widget([
                'dataProvider' => $dataProvider,
                'boxed' => false,
                'filterModel' => $model,
                'columns' => [
                    'checkbox',
                    'client', 'server',
                    'user_comment', 'time',
                ],
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php $page->end() ?>
