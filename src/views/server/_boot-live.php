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

if ($model->isLiveCDSupported()) {
    $os_items = [];
    foreach ($osimageslivecd as $item) {
        $js         = "$(this).closest('form').find('.livecd-osimage').val('{$item['osimage']}').end().submit(); $(this).closest('button').button('loading');";
        $os_items[] = [
            'label'   => $item['os'] . ' ' . $item['bitwise'],
            'url'     => '#',
            'options' => [
                'onclick' => new \yii\web\JsExpression($js),
            ],
        ];
    }

    $model->scenario = 'boot-live';
    ModalButton::begin([
        'model'    => $model,
        'button'   => [
            'label'    => Yii::t('hipanel:server', 'Boot LiveCD'),
            'class'    => 'btn btn-default btn-block',
            'disabled' => !$model->isOperable(),
            'position' => ModalButton::BUTTON_IN_MODAL,
        ],
        'modal'    => [
            'header'        => Html::tag('h4', Yii::t('hipanel:server', 'Confirm booting from Live CD')),
            'headerOptions' => ['class' => 'label-info'],
            'footer'        => \yii\bootstrap\ButtonDropdown::widget([
                'label'    => Yii::t('hipanel:server', 'Boot LiveCD'),
                'dropdown' => [
                    'items' => $os_items,
                ],
                'options'  => [
                    'class'             => 'btn btn-info',
                    'data-loading-text' => '<i class="fa fa-circle-o-notch fa-spin"></i> ' . Yii::t('hipanel', 'loading'),
                ],
            ]),
        ],
    ]);
    echo Html::hiddenInput('osimage', null, ['class' => 'livecd-osimage']);
    echo Yii::t('hipanel:server', 'This action will shutdown the server and boot live cd image');

    ModalButton::end();
}
