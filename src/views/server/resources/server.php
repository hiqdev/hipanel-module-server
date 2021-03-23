<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\finance\widgets\ResourceDetailViewer;
use hipanel\modules\server\grid\ServerGridView;
use hiqdev\hiart\ActiveDataProvider;
use yii\db\ActiveRecordInterface;

/** @var ActiveRecordInterface $originalModel */
/** @var ActiveRecordInterface $model */
/** @var ActiveDataProvider $dataProvider */
/** @var IndexPageUiOptions $uiModel */

$this->title = $originalModel->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['@server/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Server resources'), 'url' => ['@server/resource-list']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-3">
        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $originalModel->dc ?></h3>
            </div>
            <div class="box-body no-padding">
                <?= ServerGridView::detailView([
                    'boxed' => false,
                    'model' => $originalModel,
                    'columns' => [
                        'client_id', 'seller_id',
                        [
                            'attribute' => 'name',
                            'contentOptions' => ['class' => 'text-bold'],
                        ], 'detailed_type',
                        'ip', 'note', 'label',
                        'mails_num',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <?= ResourceDetailViewer::widget([
            'dataProvider' => $dataProvider,
            'originalContext' => $this->context,
            'originalModel' => $originalModel,
            'originalSearchModel' => $model,
            'uiModel' => $uiModel,
            'configurator' => Yii::$container->get('server-resource-config'),
        ]) ?>
    </div>
</div>
