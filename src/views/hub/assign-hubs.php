<?php

use hipanel\modules\server\models\AssignHubsInterface;
use hipanel\modules\server\widgets\AssignHubsPage;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/** @var AssignHubsInterface $model */
/** @var AssignHubsInterface[] $models */

$this->title = Yii::t('hipanel:server:hub', 'Assign hubs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Switches'), 'url' => ['index']];
if (count($models) === 1) {
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'assign-switches-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-switches-form', 'scenario' => 'default']),
]) ?>

<?= AssignHubsPage::widget(['models' => $models, 'form' => $form]) ?>

<?php ActiveForm::end() ?>
