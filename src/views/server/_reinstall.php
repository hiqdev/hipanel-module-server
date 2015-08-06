<?php
use hipanel\modules\server\assets\OsSelectionAsset;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Json;

echo Html::a('<i class="ion-ios-cog-outline"></i>' . Yii::t('app', 'Reinstall OS'), '#', [
    'data-toggle' => 'modal',
    'data-target' => "#modal_{$model->id}_reinstall",
]);

echo Html::beginForm(['reinstall'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
echo Html::hiddenInput('id', $model->id);
Modal::begin([
    'id'            => "modal_{$model->id}_reinstall",
    'toggleButton'  => false,
    'header'        => Html::tag('h4', Yii::t('app', 'Please, select the operating system you want to install')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer'        => Html::button(Yii::t('app', 'Reinstall'), [
        'class'             => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Reinstalling started...'),
        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading');")
    ])
]);
?>
    <div class="callout callout-warning">
        <h4><?= Yii::t('app', 'This will cause full data loss!') ?></h4>
    </div>
<?= Html::hiddenInput('osimage', null, ['class' => "reinstall-osimage"]) ?>
<?= Html::hiddenInput('panel', null, ['class' => "reinstall-panel"]) ?>
    <div class="row os-selector">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?= \Yii::t('app', 'OS') ?></div>
                <div class="list-group">
                    <?php
                    foreach ($grouped_osimages['vendors'] as $vendor) { ?>
                        <div class="list-group-item">
                            <h4 class="list-group-item-heading"><?= $vendor['name'] ?></h4>

                            <div class="list-group-item-text os-list">
                                <? foreach ($vendor['oses'] as $system => $os) {
                                    echo Html::tag('div', Html::radio('os', false, [
                                        'label' => $os,
                                        'value' => $system,
                                        'class' => 'radio'
                                    ]), ['class' => 'radio']);
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?= \Yii::t('app', 'Panel and soft') ?></div>
                <div class="list-group">
                    <?php
                    foreach ($panels as $panel => $panel_name) { ?>
                        <div class="list-group-item soft-list"
                             data-panel="<?= $panel ?>">
                            <h4 class="list-group-item-heading"><?= Yii::t('app', $panel_name) ?></h4>

                            <div class="list-group-item-text">
                                <?php foreach ($grouped_osimages['softpacks'][$panel] as $softpack) { ?>
                                    <div class="radio">
                                        <label>
                                            <?= Html::radio('panel_soft', false, [
                                                'data'  => [
                                                    'panel-soft' => 'soft',
                                                    'panel'      => $panel
                                                ],
                                                'value' => $softpack['name']
                                            ]) ?>
                                            <strong><?= $softpack['name'] ?></strong>
                                            <small style="font-weight: normal"><?= $softpack['description'] ?></small>
                                            <a class="softinfo-bttn glyphicon glyphicon-info-sign" href="#"></a>
                                            <div class="soft-desc" style="display: none;"></div>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php Modal::end();
echo Html::endForm();

OsSelectionAsset::register($this);
$this->registerJs("
    var osparams = " . Json::encode($grouped_osimages['oses']) . ";
    $('.os-selector').osSelector({
        osparams: osparams
    });
", \yii\web\View::POS_READY, 'os-selector-init'); ?>