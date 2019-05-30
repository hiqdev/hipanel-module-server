<?php

/** @var \hipanel\modules\server\forms\ConfigForm $model */
/** @var \hipanel\modules\server\forms\ConfigForm[] $models */

use hipanel\helpers\Url;
use yii\widgets\ActiveForm;


$this->title = Yii::t('hipanel', 'Create');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('hipanel:server:config', 'Configuration'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

?>


<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]); ?>

<?= $this->render('_form', compact(['model', 'models', 'form'])) ?>

<?php $form->end() ?>
