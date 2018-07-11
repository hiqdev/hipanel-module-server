<?php

namespace hipanel\server\widgets;

use hipanel\widgets\Box;
use Yii;

?>

<div class="col-md-3">
    <?php
    $box = Box::begin(['renderBody' => false]);
        $box->beginHeader();
            echo $box->renderTitle(Yii::t('hipanel:server', 'Server'));
        $box->endHeader();
        $box->beginBody();
            echo $form->field($model, 'name')->input('text')->label(Yii::t('hipanel:server', 'Server'));
            echo $form->field($model, 'dc')->input('text')->label(Yii::t('hipanel:server', 'DC'));
            /* $form->field($model, 'type')->dropDownList($deviceTypes) **/
        $box->endBody();
    $box->end()
    ?>
</div>
<div class="col-md-9">
    <?php
    $box = Box::begin(['renderBody' => false]);
        $box->beginHeader();
            echo $box->renderTitle(Yii::t('hipanel:server', 'Services'));
        $box->endHeader();
        $box->beginBody();
        $box->endBody();
    $box->end()
    ?>
</div>

