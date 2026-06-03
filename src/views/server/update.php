<?php

use hipanel\modules\server\forms\ServerForm;
use yii\helpers\Html;

/** @var ServerForm $model */
/** @var ServerForm[] $models */

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
if (count($models) === 1) {
    $this->params['breadcrumbs'][] = ['label' => Html::encode($model->name), 'url' => ['view', 'id' => (int)$model->id]];
}
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', ['model' => $model, 'models' => $models]); ?>
