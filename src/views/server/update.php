<?php

use yii\helpers\Html;

/** @var \hipanel\modules\server\forms\ServerForm $model */
/** @var \hipanel\modules\server\forms\ServerForm[] $models */

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($model->name), 'url' => ['view', 'id' => (int)$model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models'])); ?>

