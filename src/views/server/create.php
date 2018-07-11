<?php

/** @var \hipanel\modules\server\forms\ServerForm $model */
/** @var \hipanel\modules\server\forms\ServerForm[] $models */

$this->title = Yii::t('hipanel', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models'])) ?>

