<?php

use hidev\components\View;
use hipanel\models\IndexPageUiOptions;
use hipanel\modules\server\grid\IrsGridView;
use hipanel\modules\server\grid\IrsRepresentations;
use hipanel\modules\server\models\IrsSearch;
use hipanel\widgets\IndexPage;
use hiqdev\hiart\ActiveDataProvider;

/**
 * @var View $this
 * @var IrsSearch $model
 * @var array $billTypesList
 * @var array $clientTypes
 * @var ActiveDataProvider $dataProvider
 * @var IndexPageUiOptions $uiModel
 * @var IrsRepresentations $representationCollection
 */

$this->title = Yii::t('hipanel.server.irs', 'Available dedicated servers');
$this->params['breadcrumbs'][] = $this->title;
$this->params['subtitle'] = Yii::t('hipanel.server.irs', 'that are already installed in DCs');

$additionalInfo = Yii::t(
    'hipanel.server.irs',
    'In case of termination of your server rental within the first year, an additional <b>€80 setup fee</b> will be charged.'
);

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider, 'layout' => 'noSearch']) ?>

    <?php $page->content('representation-actions', $page->renderRepresentations($representationCollection)) ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= IrsGridView::widget([
                'boxed' => false,
                'resizableColumns' => false,
                'layout' => <<<"HTML"
                    <div class='row'>
                        <div class='col-xs-12'>
                            {sorter}
                        </div>
                        <div class='col-xs-12' style="color: #8a6d3b; text-decoration: underline;">
                            <p style="padding: 0 15px">$additionalInfo</p>
                        </div>
                    </div>
                    <div class='table-responsive'>{items}</div>
                    <div class='row'>
                        <div class='col-xs-12' style="display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-between;">
                            <div class='dataTables_info'>{summary}</div>
                            <div class='dataTables_paginate paging_bootstrap'>{pager}</div>
                        </div>
                    </div>
                HTML,
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php $page->end() ?>
