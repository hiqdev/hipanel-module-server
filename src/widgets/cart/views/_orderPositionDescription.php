<?php

/**
 * @var $position ServerOrderProduct|ServerOrderDedicatedProduct
 */

use hipanel\modules\server\cart\ServerOrderDedicatedProduct;
use hipanel\modules\server\cart\ServerOrderProduct;
use hipanel\modules\server\widgets\OSFormatter;
use yii\helpers\Html;

echo $position->getIcon();
echo '&nbsp';
echo $position->name;
echo '&nbsp';
echo Html::tag('span', Yii::t('hipanel:server', 'Server ordering'), ['class' => 'text-muted']);
?>

<br/>
<?php if ($position->label) : ?>
    <?= Html::tag('b', Yii::t('hipanel', 'Label')) ?>: <?= $position->label ?>
<?php endif; ?>
<br>
<b><span><?= Yii::t('hipanel:server:os', 'OS') ?>:</span></b> <?= OSFormatter::widget(['osimage' => $position->getImage()]); ?>
<br/>
<b><span><?= Yii::t('hipanel:server:os', 'Soft package') ?>:</span></b> <?= $position->getImage()->getDisplaySoftPackName() ?>
<br/>
<b><span><?= Yii::t('hipanel:server:os', 'Panel') ?>:</span></b> <?= $position->getImage()->getDisplayPanelName() ?>
<br/>
