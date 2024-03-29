<?php

use hipanel\modules\server\models\Change;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\IndexPage;
use hiqdev\hiart\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var OsimageSearch $osimages
 * @var array $states
 * @var Change $model
 * @var ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('hipanel:server', 'Pending confirmation servers');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
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
                    'scenario' => 'approve',
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
        <?php if (Yii::$app->user->can('delete-bills')) : ?>
            <?= $page->renderBulkDeleteButton('delete') ?>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= \hipanel\modules\server\grid\PreOrderGridView::widget([
                'dataProvider' => $dataProvider,
                'boxed' => false,
                'filterModel' => $model,
                'columns' => [
                    'checkbox', 'client',
                    'user_comment', 'tech_comment', 'time',
                ],
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php $page->end() ?>
