<?php

use hipanel\helpers\Url;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\TariffCombo;
use hipanel\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \hipanel\modules\server\models\Server $model
 */

$defaultDateTime = new DateTime('first day of this month 00:00');

?>

<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#bulk" aria-controls="home" role="tab" data-toggle="tab"><?= Yii::t('hipanel', 'Set for all') ?></a>
        </li>
        <li role="presentation">
            <a href="#by-one" aria-controls="profile" role="tab" data-toggle="tab"><?= Yii::t('hipanel', 'Set by one') ?></a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="row">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="bulk">
                <div class="col-md-12" style="margin-top: 15pt;">
                    <?php $form = ActiveForm::begin([
                        'id' => 'bulk-sale',
                        'action' => Url::toRoute('@server/bulk-sale'),
                        'enableAjaxValidation' => true,
                        'validateOnBlur' => true,
                        'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'sale']),
                    ]) ?>

                    <?php foreach ($models as $model) : ?>
                        <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
                        <?= Html::activeHiddenInput($model, "[$model->id]name") ?>
                    <?php endforeach; ?>

                    <div class="panel panel-default">
                        <div class="panel-heading"><?= Yii::t('hipanel:server', 'Affected servers') ?></div>
                        <div class="panel-body">
                            <?= \hipanel\widgets\ArraySpoiler::widget([
                                'data' => $models,
                                'visibleCount' => count($models),
                                'formatter' => function ($model) {
                                    return $model->name;
                                },
                                'delimiter' => ',&nbsp; ',
                            ]); ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'client_id')->widget(ClientCombo::class, [
                        'inputOptions' => [
                            'name' => 'client_id'
                        ]
                    ])->hint(Yii::t('hipanel:server', 'Clear this input to unsale the servers')) ?>
                    <?= $form->field($model, 'tariff_id')->widget(TariffCombo::class, [
                        'tariffType' => 'server',
                        'inputOptions' => [
                            'name' => 'tariff_id'
                        ]
                    ]) ?>
                    <?= $form->field($model, 'sale_time')->widget(DateTimePicker::class, [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd hh:ii:ss',
                        ],
                        'options' => [
                            'value' => Yii::$app->formatter->asDatetime($defaultDateTime, 'php:Y-m-d H:i:s'),
                            'name' => 'sale_time'
                        ],
                    ]) ?>

                    <?= $form->field($model, 'move_accounts')->checkbox(['name' => 'move_accounts']) ?>

                    <hr>
                    <?= Html::submitButton(Yii::t('hipanel:server', 'Sell'), ['class' => 'btn btn-success', 'id' => 'save-button']) ?>
                    <?php ActiveForm::end() ?>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="by-one">
                    <div class="col-md-12" style="margin-top: 15px;">
                        <?php $form = ActiveForm::begin([
                            'id' => 'bulk-by-one-sale',
                            'action' => Url::toRoute('@server/bulk-sale'),
                            'enableAjaxValidation' => true,
                            'validateOnBlur' => true,
                            'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'sale']),
                        ]); ?>


                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'client_id')->widget(ClientCombo::class, [
                                    'options' => ['id' => 'bulk-by-one-sale-client'],
                                    'inputOptions' => [
                                        'id' => 'bulk-by-one-sale-client',
                                        'name' => 'client_id'
                                    ],
                                    'formElementSelector' => '.form-group',
                                ])->hint(Yii::t('hipanel:server', 'Clear this input to unsale the servers')) ?>
                            </div>

                            <?php foreach ($models as $model) : ?>
                                <div class="col-md-3 text-right" style="line-height: 34px;">
                                    <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
                                    <strong><?= $model->name ?></strong>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, "[$model->id]tariff_id")
                                                ->widget(TariffCombo::class, [
                                                    'tariffType' => 'server',
                                                    'inputOptions' => ['ref' => 'tariff-combo']
                                                ])
                                                ->label(false)
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, "[$model->id]sale_time")->widget(DateTimePicker::class, [
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'format' => 'yyyy-mm-dd hh:ii:ss',
                                                ],
                                                'options' => [
                                                    'value' => Yii::$app->formatter->asDatetime($defaultDateTime, 'php:Y-m-d H:i:s'),
                                                    'ref' => 'sale-time-combo'
                                                ],
                                            ])->label(false) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>

                        <div class="col-md-12">
                            <?= $form->field($model, 'move_accounts')->checkbox(['name' => 'move_accounts']) ?>

                            <hr>
                            <?= Html::submitButton(Yii::t('hipanel:server', 'Sell'), ['class' => 'btn btn-success', 'id' => 'save-button']) ?>
                            <?php ActiveForm::end() ?>
                        </div>

                    </div>

            </div>
        </div>
    </div>
</div>

<?= $this->registerJs(<<<JS

JS
); ?>

<?= \hipanel\widgets\BulkAssignmentFieldsLinker::widget([
    'inputSelectors' => ['select[ref=tariff-combo]', 'input[ref=sale-time-combo]']
]) ?>
