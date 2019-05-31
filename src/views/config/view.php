<?php

use hipanel\modules\server\models\Config;
use hipanel\modules\server\menus\ConfigDetailMenu;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;
use yii\web\View;


/**
 * @var View $this
 * @var Config $model
 */
$this->title = Html::encode($model->name);
$this->params['subtitle'] = Yii::t('hipanel:server:config', 'Config detailed information');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$linkTemplate = '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>';
?>
<div class="row">
    <div class="col-md-3">
        <?= MainDetails::widget([
            'title' => 'Configuration',
            'icon' => 'fa-cogs',
            'subTitle' => Html::a($model->client, ['@client/view', 'id' => $model->client_id]),
            'menu' => ConfigDetailMenu::widget(['model' => $model], [
                'linkTemplate' => $linkTemplate,
            ]),
        ]) ?>
    </div>
</div>