<?php

$this->title = Yii::t('hipanel/server/order', 'XEN SSD');
$this->breadcrumbs->setItems([
    ['label' => Yii::t('hipanel/server/order', 'Buy server'), 'url' => ['index']],
    $this->title
]);

echo $this->render('_priceBox', compact('packages', 'tariffTypes', 'testVDSPurchased')); ?>


