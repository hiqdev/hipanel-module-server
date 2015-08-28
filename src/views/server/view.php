<?php

use hipanel\modules\server\assets\OsSelectionAsset;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\widgets\Box;
use hipanel\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title    = $model->name;
$this->subtitle = Yii::t('app', 'server detailed information') . ' #' . $model->id;
$this->breadcrumbs->setItems([
    ['label' => 'Servers', 'url' => ['index']],
    $this->title,
]);
?>

<div class="row">
    <div class="col-md-3">
        <?php Box::begin(); ?>
        <div class="profile-user-img text-center">
            <i class="fa fa-server fa-5x"></i>
        </div>
        <p class="text-center">
            <span class="profile-user-role"><?= $model->name ?></span>
            <br>
            <span class="profile-user-name"><?= $model->client . ' / ' . $model->seller; ?></span>
        </p>
        <?php Pjax::begin(['enablePushState' => false]) ?>
        <div class="profile-usermenu">
            <ul class="nav">
                <?php if ($model->isPwChangeSupported()) { ?>
                    <li>
                        <?= $this->render('_reset-password', compact(['model'])) ?>
                    </li>
                <?php } ?>
                <li>
                    <?= $this->render('_reinstall', compact(['model', 'grouped_osimages', 'panels'])) ?>
                </li>
                <?php if (Yii::$app->user->can('support')) { ?>
                    <li>
                        <?= $this->render('_delete', compact(['model'])) ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <?php Pjax::end() ?>
        <?php Box::end(); ?>
    </div>

    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                        echo $box->renderTitle(Yii::t('app', 'Server information'));
                    $box->endHeader();
                    $box->beginBody();
                        echo ServerGridView::detailView([
                            'boxed'   => false,
                            'model'   => $model,
                            'gridOptions' => [
                                'osImages' => $osimages,
                            ],
                            'columns' => [
                                'client_id', 'seller_id', 'note', 'label',
                                ['attribute' => 'name'],
                                'state', 'ips', 'os', 'panel'
                            ],
                        ]);
                    $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
        <div class="row">
            <?php Pjax::begin(['enablePushState' => false]) ?>
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                        echo $box->renderTitle(Yii::t('app', 'Financial information'));
                    $box->endHeader();
                    $box->beginBody();
                        echo ServerGridView::detailView([
                            'boxed'   => false,
                            'model'   => $model,
                            'columns' => [
                                'tariff', 'tariff_note', 'sale_time', 'discount', 'expires',
                            ],
                        ]);
                    $box->endBody();
                    $box->beginFooter();
                        echo $this->render('_refuse', compact(['model']));
                    $box->endFooter();
                $box->end();
                ?>
            </div>
            <?php Pjax::end() ?>
        </div>
    </div>
    <?php Pjax::begin(['enablePushState' => false]) ?>
    <div class="col-md-4">
        <?php if ($model->isVNCSupported()) { ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                        echo $box->renderTitle(Yii::t('app', 'VNC server'));
                    $box->endHeader();
                    $box->beginBody();
                        echo $this->render('_vnc', compact(['model']));
                    $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                    echo $box->renderTitle(Yii::t('app', 'System management'));
                $box->endHeader();
                $box->beginBody();
                    echo $this->render('_reboot', compact(['model']));
                    echo $this->render('_shutdown', compact(['model']));
                    if ($model->isLiveCDSupported()) {
                        echo $this->render('_boot-live', compact(['model', 'osimageslivecd']));
                    }
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                    $box->beginHeader();
                        echo $box->renderTitle(Yii::t('app', 'Power management'));
                    $box->endHeader();
                    $box->beginBody();
                        echo $this->render('_reset', compact(['model']));
                        echo $this->render('_power-off', compact(['model']));
                        echo $this->render('_power-on', compact(['model']));
                    $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
    </div>
    <?php Pjax::end() ?>
</div>

<?php
$this->registerCss("th { white-space: nowrap; }");