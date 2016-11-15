<?php

/**
 * @var \hipanel\modules\server\cart\ServerOrderProduct $position
 */
use yii\helpers\Html;

echo $position->getIcon();
echo '&nbsp';
echo $position->name;
echo '&nbsp';
echo Html::tag('span', Yii::t('hipanel:server', 'Server ordering'), ['class' => 'text-muted']);
?>

<br />
<b><span><?= Yii::t('hipanel:server:os', 'OS') ?>:</span></b> <?= \hipanel\modules\server\widgets\OSFormatter::widget(['osimage' => $position->getImage()]); ?><br />
<b><span><?= Yii::t('hipanel:server:os', 'Soft package') ?>:</span></b> <?= $position->getImage()->getDisplaySoftPackName() ?><br />
<b><span><?= Yii::t('hipanel:server:os', 'Panel') ?>:</span></b> <?= $position->getImage()->getDisplayPanelName() ?><br />
