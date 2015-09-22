<?php
use hipanel\modules\server\assets\OsSelectionAsset;
use yii\bootstrap\Modal;
use hipanel\widgets\ModalButton;

use yii\helpers\Html;
use yii\helpers\Json;

$model->scenario = 'reinstall';
ModalButton::begin([
    'model'    => $model,
    'button'   => ['label' => '<i class="ion-ios-cog-outline"></i>' . Yii::t('app', 'Reinstall OS')],
    'modal'    => [
        'header'        => Html::tag('h4', Yii::t('app', 'Please, select the operating system you want to install')),
        'headerOptions' => ['class' => 'label-info'],
        'footer'        => [
            'label'             => Yii::t('app', 'Reinstall'),
            'data-loading-text' => Yii::t('app', 'Resinstalling started...'),
            'class'             => 'btn btn-warning',
        ]
    ]
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
                                <?php if (is_array($grouped_osimages['softpacks'][$panel])) {
                                    foreach ($grouped_osimages['softpacks'][$panel] as $softpack) { ?>
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
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php OsSelectionAsset::register($this);
$this->registerJs("
    var osparams = " . Json::encode($grouped_osimages['oses']) . ";
    $('.os-selector').osSelector({
        osparams: osparams
    });
", \yii\web\View::POS_READY, 'os-selector-init');

ModalButton::end();