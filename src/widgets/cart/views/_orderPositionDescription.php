<?php

/**
 * @var $position ServerOrderProduct|ServerOrderDedicatedProduct
 */

use hipanel\modules\server\cart\ServerOrderDedicatedProduct;
use hipanel\modules\server\cart\ServerOrderProduct;
use hipanel\modules\server\widgets\OSFormatter;
use yii\helpers\Html;

?>

<?= Html::tag('span', implode('&nbsp;', [
    $position->getIcon(),
    $position->name,
    Html::tag('span', Yii::t('hipanel:server', 'Server ordering'), ['class' => 'text-muted']),
])) ?>

<?= $this->context->formatConfig([
    Yii::t('hipanel:server:order', 'Label') => $position->label,
    Yii::t('hipanel:server:order', 'CHASSIS') => $position->getModel()->label,
    Yii::t('hipanel:server:order', 'CPU') => $position->getModel()->cpu,
    Yii::t('hipanel:server:order', 'RAM') => $position->getModel()->ram,
    Yii::t('hipanel:server:order', 'HDD') => $position->getModel()->hdd,
    Yii::t('hipanel:server:order', 'SSD') => $position->getModel()->ssd,
    Yii::t('hipanel:server:order', 'Traffic') => $position->getModel()->traffic,
    Yii::t('hipanel:server:order', 'Administration') => $position->getDisplayAdministration(),
    Yii::t('hipanel:server:os', 'OS') => OSFormatter::widget(['osimage' => $position->getImage()]),
    Yii::t('hipanel:server:os', 'Soft package') => $position->getImage()->getDisplaySoftPackName(),
    Yii::t('hipanel:server:os', 'Panel') => $position->getImage()->getDisplayPanelName(),
    Yii::t('hipanel:server:order', 'Location') => $position->getDisplayLocation(),
]) ?>
