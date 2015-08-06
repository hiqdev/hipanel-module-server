<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;

if ($model->isLiveCDSupported()) {
    echo Html::beginForm(['boot-live'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
    echo Html::hiddenInput('id', $model->id);

    $os_items = [];
    foreach ($osimageslivecd as $item) {
        $js         = "$(this).closest('form').find('.livecd-osimage').val({$item['osimage']}).end().submit(); $(this).closest('button').button('loading');";
        $os_items[] = [
            'label'   => $item['os'] . ' ' . $item['bitwise'],
            'url'     => '#',
            'onclick' => new \yii\web\JsExpression($js)
        ];

    }
    Modal::begin([
        'toggleButton' => [
            'label'    => Yii::t('app', 'Boot LiveCD'),
            'class'    => 'btn btn-default',
            'disabled' => !$model->isOperable(),
        ],
        'header'       => Html::tag('h4', Yii::t('app', 'Confirm boot from Live CD')),
        'footer'       => \yii\bootstrap\ButtonDropdown::widget([
            'label'    => 'Boot LiveCD',
            'dropdown' => [
                'items' => $os_items
            ],
            'options'  => [
                'class'             => 'btn btn-info',
                'data-loading-text' => Yii::t('app', 'Resetting password...'),
            ]
        ])
    ]);
    echo Html::hiddenInput('osimage', null, ['class' => 'livecd-osimage']);
    ?>
    Это приведет к отключению сервера и загрузке образа Live CD.
    <?php Modal::end();
    echo Html::endForm();
}