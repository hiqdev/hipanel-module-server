<?php

use hipanel\modules\server\models\Config;
use hipanel\modules\server\models\Osimage;
use hipanel\server\order\yii\widgets\ServerOrder;

/** @var Config[] $configs */
/** @var Osimage[] $images */

$this->title = Yii::t('hipanel:server:panel', 'Dedicated servers');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= ServerOrder::widget(compact('configs', 'images')) ?>
