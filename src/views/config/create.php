<?php

/** @var \hipanel\modules\server\forms\ConfigForm $model */
/** @var \hipanel\modules\server\forms\ConfigForm[] $models */

$this->title = Yii::t('hipanel:server:config', 'Create configuration');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('hipanel:server:config', 'Configuration'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models'])) ?>
