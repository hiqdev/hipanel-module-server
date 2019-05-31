<?php

/** @var \hipanel\modules\server\forms\ConfigForm $model */
/** @var \hipanel\modules\server\forms\ConfigForm[] $models */

$this->title = Yii::t('hipanel', 'Edit');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('hipanel:server:config', 'Configs'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models', 'form'])) ?>
