<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\widgets\AdvancedSearch;
use hiqdev\combo\StaticCombo;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

?>

<?php $form = AdvancedSearch::begin(compact('model')) ?>
    <div class="col-md-4">
        <?= $form->field('name_like') ?>
        <?= $form->field('note') ?>
    </div>

    <div class="col-md-4">
        <?= $form->field('client_id')->widget(ClientCombo::classname()) ?>
        <?= $form->field('seller_id')->widget(ClientCombo::classname()) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field('state')->widget(StaticCombo::classname(), [
            'data' => $state_data,
            'hasId' => true,
            'pluginOptions' => [
                'select2Options' => [
                    'multiple' => true,
                ]
            ],
        ]) ?>
    </div>

    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php $form::end() ?>
