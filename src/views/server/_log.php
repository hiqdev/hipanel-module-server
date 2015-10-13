<?php
use yii\helpers\Html;

if (empty($model->statuses)) {
    echo Yii::t('app', 'No events were recorded');
} else { ?>
    <table class="table table-condensed">
        <tr>
            <th><?= Yii::t('app', 'Event') ?></th>
            <th><?= Yii::t('app', 'Time') ?></th>
        </tr>
        <?php foreach ($model->statuses as $status => $time) {
            echo Html::beginTag('tr');
            echo Html::tag('td', Yii::t('synt', $status));
            echo Html::tag('td', Yii::$app->formatter->asDatetime($time));
            echo Html::endTag('tr');
        } ?>
    </table>
<?php } ?>
