<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

$this->title = Yii::t('hipanel:server:order', 'Open VZ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:server:order', 'Order server'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_priceBox', compact('packages', 'tariffTypes', 'testVDSPurchased'));
