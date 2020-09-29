<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\finance\widgets\ResourceDetailViewer;
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
    'originalSearchModel' => $model,
    'uiModel' => $uiModel,
    'configurator' => Yii::$container->get('server-resource-config'),
]) ?>
