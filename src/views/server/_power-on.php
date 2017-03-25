<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

use hipanel\widgets\ModalButton;
use yii\helpers\Html;

echo ModalButton::widget([
    'model'    => $model,
    'scenario' => 'power-on',
    'button'   => [
        'label'    => Yii::t('hipanel:server', 'Power on'),
        'class'    => 'btn btn-default btn-block',
        'disabled' => !$model->isOperable(),
    ],
    'body'     => Yii::t('hipanel:server', 'Turn ON server power?'),
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('hipanel:server', 'Confirm server power ON')),
        'headerOptions' => ['class' => 'label-info'],
        'footer'        => [
            'label'             => Yii::t('hipanel:server', 'Power ON'),
            'data-loading-text' => Yii::t('hipanel:server', 'Turning power ON...'),
            'class'             => 'btn btn-info',
        ],
    ],
]);
