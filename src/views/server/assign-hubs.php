<?php

use hipanel\helpers\Url;
use hipanel\modules\server\models\AssignSwitchInterface;
use hipanel\modules\server\widgets\AssignSwitchesPage;
use yii\bootstrap\ActiveForm;

/** @var AssignSwitchInterface $model */
/** @var AssignSwitchInterface[] $models */

$this->title = Yii::t('hipanel:server', 'Assign hubs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
if (count($models) === 1) {
    $this->params['breadcrumbs'][] = ['label' => reset($models)->name, 'url' => ['view', 'id' => reset($models)->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => 'assign-hubs-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-assign-hubs-form', 'scenario' => 'default']),
]) ?>

<?= AssignSwitchesPage::widget(['models' => $models, 'form' => $form]) ?>
