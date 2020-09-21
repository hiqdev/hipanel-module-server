<?php

use hipanel\widgets\ResourceListViewer;
use hiqdev\hiart\ActiveDataProvider;
use yii\db\ActiveRecordInterface;

/** @var ActiveRecordInterface $originalModel */
/** @var ActiveRecordInterface $model */
/** @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('hipanel', 'Server resources');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['@server/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= ResourceListViewer::widget([
    'dataProvider' => $dataProvider,
    'originalContext' => $this->context,
    'searchModel' => $model,
    'originalModel' => $originalModel,
    'uiModel' => $uiModel,
]) ?>
