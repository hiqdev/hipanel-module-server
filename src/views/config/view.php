<?php

use hipanel\modules\server\models\Config;
use yii\web\View;


/**
 * @var View $this
 * @var Config $model
 */
$this->title = $model->name;
$this->params['subtitle'] = Yii::t('hipanel:server:config', 'Config detailed information');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
