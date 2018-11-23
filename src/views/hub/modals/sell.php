<?php

use hipanel\modules\server\widgets\SellForm;
use yii\helpers\Url;

?>

<?= SellForm::widget([
    'models' => $models,
    'actionUrl' => Url::toRoute('@hub/sell'),
    'validationUrl' => Url::toRoute(['validate-sell-form', 'scenario' => 'sell']),
]) ?>
