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
    'scenario' => 'reset-password',
    'button'   => ['label' => '<i class="fa fa-refresh"></i>' . Yii::t('hipanel:server', 'Reset root password')],
    'body'     => Yii::t('hipanel:server',
        'Are you sure you want to reset the root password on {name} server? You will get your new root password on the e-mail.',
        ['name' => $model->name]),
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('hipanel:server', 'Confirm root password resetting')),
        'headerOptions' => ['class' => 'label-danger'],
        'footer'        => [
            'label'             => Yii::t('hipanel:server', 'Reset root password'),
            'data-loading-text' => Yii::t('hipanel:server', 'Resetting...'),
            'class'             => 'btn btn-danger',
        ],
    ],
]);
