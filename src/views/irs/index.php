<?php

use hidev\components\View;
use hipanel\models\IndexPageUiOptions;
use hipanel\modules\server\grid\IrsGridView;
use hipanel\modules\server\grid\IrsRepresentations;
use hipanel\modules\server\models\IrsSearch;
use hipanel\widgets\IndexPage;
use hiqdev\hiart\ActiveDataProvider;
use yii\helpers\Html;

/**
 * @var View $this
 * @var IrsSearch $model
 * @var array $billTypesList
 * @var array $clientTypes
 * @var ActiveDataProvider $dataProvider
 * @var IndexPageUiOptions $uiModel
 * @var IrsRepresentations $representationCollection
 */

$this->title = Yii::t('hipanel.server.irs', 'Instant rent servers');
$this->params['breadcrumbs'][] = $this->title;
$this->params['subtitle'] = Yii::t('hipanel.server.irs', 'that are already installed in DCs');

$infoHeader = Html::beginTag('ul', ['class' => 'list-unstyled text-muted text-sm', 'style' => 'padding: 0 1em 1em;']);
$infoHeader .= Html::tag('li', Yii::t('hipanel.server.irs', 'The servers available for rental are pre-mounted in our racks and cannot be relocated. However, it is possible to connect newly rented servers to existing ones through VXLAN.'));
$infoHeader .= Html::tag('li', Yii::t('hipanel.server.irs', 'Delivery time - 4h*'));
$infoHeader .= Html::tag('li', Yii::t('hipanel.server.irs', 'Any changes and upgrades are possible but with extended delivery time.'));
$infoHeader .= Html::tag('li',
    Yii::t('hipanel.server.irs',
        '*For initial orders placed at this location, there may be a slight delay as we verify customer information and process the order for acceptance.'));
$infoHeader .= Html::endTag('ul');

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider, 'layout' => 'noSearch']) ?>

    <?php $page->content('sorter-actions', $page->renderSorter(['attributes' => ['id']])) ?>
    <?php $page->content('representation-actions', $page->renderRepresentations($representationCollection)) ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= IrsGridView::widget([
                'boxed' => false,
                'resizableColumns' => false,
                'layout' => <<<"HTML"
                    <div class='row'>
                        <div class='col-xs-11'>
                            {sorter}
                        </div>
                        <div class="col-xs-12">
                            $infoHeader
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
