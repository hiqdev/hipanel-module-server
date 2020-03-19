<?php

/**
 * @var $position ServerOrderProduct|ServerOrderDedicatedProduct
 * @var $this View
 */

use hipanel\modules\server\cart\ServerOrderDedicatedProduct;
use hipanel\modules\server\cart\ServerOrderProduct;
use hipanel\modules\server\widgets\OSFormatter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

$configuration = [
    Yii::t('hipanel:server:order', 'Label') => $position->label,
    Yii::t('hipanel:server:order', 'CHASSIS') => $position->getModel()->label,
    Yii::t('hipanel:server:order', 'CPU') => $position->getModel()->cpu,
    Yii::t('hipanel:server:order', 'RAM') => $position->getModel()->ram,
    Yii::t('hipanel:server:order', 'HDD') => $position->getModel()->hdd,
    Yii::t('hipanel:server:order', 'SSD') => $position->getModel()->ssd,
    Yii::t('hipanel:server:order', 'Traffic') => $position->getModel()->traffic,
    Yii::t('hipanel:server:order', 'Administration') => $position->getDisplayAdministration(),
    Yii::t('hipanel:server:os', 'OS') => OSFormatter::widget(['osimage' => $position->getImage()]),
    Yii::t('hipanel:server:os', 'Soft package') => $position->getImage()->getDisplaySoftPackName(),
    Yii::t('hipanel:server:os', 'Panel') => $position->getImage()->getDisplayPanelName(),
    Yii::t('hipanel:server:order', 'Location') => $position->getDisplayLocation(),
];

$timerLoading = Html::tag('i', null, ['class' => 'fa fa-circle-o-notch fa-spin fa-fw']);
$formattedTimerLoading = Json::htmlEncode(Html::tag('i', null, ['class' => 'fa fa-circle-o-notch fa-spin fa-fw']));
$reservationUrl = Json::htmlEncode(Url::to('@config/reserve'));
$timeIsOver = Json::htmlEncode(Html::tag(
    'span',
    Yii::t('hipanel:server', 'Reservation time is over, your server can be purchased by someone else! Still would like to keep it?'),
    ['class' => 'text-danger']
));
if ($position instanceof ServerOrderDedicatedProduct) {
    $now = new DateTimeImmutable();
    $exp = $position->expirationTime;
    $dif = $now->diff($exp);
    $remainingTime = (new DateTime('@0'))->add($dif);
    $takeMoreTimeBtn = Html::a(Yii::t('hipanel:server', 'Take more time'), '#', [
        'class' => 'btn bg-olive btn-flat btn-xs server-config_more-time',
        'style' => 'margin-top: .1em;',
        'data' => [
            'position-id' => $position->getId(),
            'loading-text' => Yii::t('hipanel:server', 'Reserving again...'),
        ],
    ]);
    $configuration[Yii::t('hipanel:server', 'Remaining time')] = Html::tag('span', $timerLoading, [
        'class' => 'server-config_countdown',
        'data' => [
            'remaining-time' => $remainingTime->getTimestamp() * 1000,
        ],
    ]);
    $configuration[''] = $takeMoreTimeBtn;
}

?>

<?= Html::tag('span', implode('&nbsp;', [
    $position->getIcon(),
    $position->name,
    Html::tag('span', Yii::t('hipanel:server', 'Server ordering'), ['class' => 'text-muted']),
])) ?>

<?= $this->context->formatConfig($configuration) ?>

<?php
$this->registerJs(<<<"JS"
(function () {
    const interval = 1000;
    setInterval(() => {
        $('.server-config_countdown').each(function () {
            if ($.active === 0) {
                const remainingTime = this.dataset.remainingTime - interval;
                const duration = moment.duration(remainingTime, 'milliseconds');
                if (duration <= 0) {
                    $(this).html('' + $timeIsOver);
                    
                    return;
                }
                this.dataset.remainingTime = remainingTime;
                $(this).html('<time>' + moment.utc(duration.asMilliseconds()).format('mm:ss') + '</time>');
            }
        })
    }, interval);

    $('.server-config_more-time').click(function () {
        const btn = $(this).button('loading'), timer = $(this).parents('.dl-config').eq(0).find('.server-config_countdown');
        $.ajax({
            url: '' + $reservationUrl,
            method: 'POST',
            dataType: 'json',
            data: {reservation_id: $(this).data('position-id')},
            beforeSend: () => {
                timer.html('' + $formattedTimerLoading);
            },
            success: (data) => {
                const diff = moment.utc(data.expirationTime).diff(moment.utc());
                timer.get(0).dataset.remainingTime = diff;
           },
           complete: () => {
                btn.button('reset');
           }
       });

        return false;
    });
})();
JS
);
