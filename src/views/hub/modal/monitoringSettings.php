<?php

use hipanel\modules\server\widgets\MonitoringSettingsForm;

?>

<?= MonitoringSettingsForm::widget([
    'model' => $model,
    'breadcrumbsLabel' => Yii::t('hipanel:server', 'Switches'),
    'nicMediaOptions' => $nicMediaOptions,
]) ?>

