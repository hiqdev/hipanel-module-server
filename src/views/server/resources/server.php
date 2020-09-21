<?php

use hipanel\widgets\ResourceDetailViewer;
use hiqdev\hiart\ActiveDataProvider;
use yii\db\ActiveRecordInterface;

/** @var ActiveRecordInterface $originalModel */
/** @var ActiveRecordInterface $model */
/** @var ActiveDataProvider $dataProvider */

$this->title = $originalModel->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['@server/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Server resources'), 'url' => ['@server/resource-list']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= ResourceDetailViewer::widget([
    'dataProvider' => $dataProvider,
    'originalContext' => $this->context,
    'searchModel' => $model,
    'originalModel' => $originalModel,
    'uiModel' => $uiModel,
]) ?>
