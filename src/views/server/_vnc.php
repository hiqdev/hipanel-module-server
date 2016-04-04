<?php
use hipanel\widgets\Pjax;
use yii\helpers\Html;

Pjax::begin([
    'id' => 'server-vnc-pjax',
    'enablePushState' => false,
    'enableReplaceState' => false,
]);

if ($model->vnc['enabled']) {
    echo Html::tag('span',
        Html::tag('i', '', ['class' => 'glyphicon glyphicon-ok']) . ' ' . Yii::t('hipanel/server', 'Enabled'),
        ['class' => 'label label-success']);

    $fields = [
        Yii::t('hipanel/server', 'IP') => $model->vnc['vnc_ip'],
        Yii::t('hipanel/server', 'Port') => $model->vnc['vnc_port'],
        Yii::t('hipanel/server', 'Password') => $model->vnc['vnc_password']
    ];
    ?>
    <dl class="dl-horizontal">
        <?php foreach ($fields as $name => $value) { ?>
            <dt><?= $name ?></dt>
            <dd><?= $value ?></dd>
            <?php
        } ?>
    </dl>
    <?php if (!empty($model->vnc['endTime']) && $model->vnc['endTime'] > time()) {
        echo Yii::t('hipanel/server', 'VNC will be disabled {time}',
            ['time' => Yii::$app->formatter->asRelativeTime($model->vnc['endTime'])]);
    }
} else {
    echo Html::beginForm(['enable-vnc', 'id' => $model->id], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
    echo Html::submitButton(
        Yii::t('hipanel/server', 'Enable'),
        [
            'class' => 'btn btn-success btn-block',
            'data-loading-text' => Yii::t('hipanel/server', 'Enabling...'),
            'onClick' => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')"),
            'disabled' => !$model->isOperable() || !$model->isVNCSupported(),
        ]
    );
    echo ' ';
    if (!$model->isVNCSupported()) {
        echo Yii::t('hipanel/server', 'VNC is supported only on XEN visualization');
    }
    echo Html::endForm();
}

Pjax::end();
