<?php
use yii\helpers\Html;

if (empty($model->statuses)) {
    echo Yii::t('hipanel:server', 'No events were recorded');
} else { ?>
    <table class="table table-condensed">
        <tr>
            <th><?= Yii::t('hipanel:server', 'Event') ?></th>
            <th><?= Yii::t('hipanel:server', 'Time') ?></th>
        </tr>
        <?php foreach ($model->statuses as $status => $time) {
            echo Html::beginTag('tr');
            echo Html::tag('td', Yii::t('synt', $status));
            echo Html::tag('td', Yii::$app->formatter->asDatetime($time));
            echo Html::endTag('tr');
        } ?>
    </table>
<?php } ?>
