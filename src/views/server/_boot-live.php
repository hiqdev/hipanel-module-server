<?php
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

if ($model->isLiveCDSupported()) {
    $os_items = [];
    foreach ($osimageslivecd as $item) {
        $js         = "$(this).closest('form').find('.livecd-osimage').val({$item['osimage']}).end().submit(); $(this).closest('button').button('loading');";
        $os_items[] = [
            'label'   => $item['os'] . ' ' . $item['bitwise'],
            'url'     => '#',
            'onclick' => new \yii\web\JsExpression($js)
        ];

    }

    $model->scenario = 'bool-live';
    ModalButton::begin([
        'model'    => $model,
        'button'   => [
            'label'    => Yii::t('app', 'Boot LiveCD'),
            'class'    => 'btn btn-default',
            'disabled' => !$model->isOperable(),
            'position' => ModalButton::BUTTON_IN_MODAL,
        ],
        'modal'    => [
            'header'        => Html::tag('h4', Yii::t('app', 'Confirm booting from Live CD')),
            'headerOptions' => ['class' => 'label-info'],
            'footer'        => \yii\bootstrap\ButtonDropdown::widget([
                'label'    => Yii::t('app', 'Boot LiveCD'),
                'dropdown' => [
                    'items' => $os_items
                ],
                'options'  => [
                    'class'             => 'btn btn-info',
                    'data-loading-text' => Yii::t('app', '...'),
                ]
            ])
        ]

    ]);
    echo Html::hiddenInput('osimage', null, ['class' => 'livecd-osimage']);
    echo Yii::t('app', 'This action will shutdown the server and boot live cd image');

    ModalButton::end();
}