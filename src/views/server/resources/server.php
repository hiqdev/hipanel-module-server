<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\server\helpers\ServerHelper;
use hipanel\widgets\ResourceDetailViewer;
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

<?= ResourceDetailViewer::widget([
    'dataProvider' => $dataProvider,
    'originalContext' => $this->context,
    'originalModel' => $originalModel,
    'uiModel' => $uiModel,
    'configurator' => ServerHelper::getServerResourceConfig(),
]) ?>
