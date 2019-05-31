<?php

/** @var \hipanel\modules\server\forms\ConfigForm $model */
/** @var \hipanel\modules\server\forms\ConfigForm[] $models */

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('hipanel:server:config', 'Configuration'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models', 'form'])) ?>
