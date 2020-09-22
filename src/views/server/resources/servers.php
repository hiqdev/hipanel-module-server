<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\server\helpers\ServerHelper;
use hipanel\widgets\ResourceListViewer;
use hiqdev\hiart\ActiveDataProvider;
use yii\db\ActiveRecordInterface;

/** @var ActiveRecordInterface $originalModel */
/** @var ActiveDataProvider $dataProvider */
/** @var IndexPageUiOptions $uiModel */

$this->title = Yii::t('hipanel', 'Server resources');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['@server/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= ResourceListViewer::widget([
    'dataProvider' => $dataProvider,
    'originalContext' => $this->context,
    'uiModel' => $uiModel,
    'configurator' => ServerHelper::getServerResourceConfig(),
]) ?>
