<?php

use hipanel\base\View;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\widgets\ActionBox;
use hipanel\widgets\BulkButtons;
use hipanel\widgets\LinkSorter;
use hipanel\widgets\Pjax;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;

/**
 * @var OsimageSearch $osimages
 */

/**
 * @var View $this
 */
$this->title    = Yii::t('app', 'Servers');
$this->subtitle = Yii::t('app', Yii::$app->request->queryParams ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title
]);

Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true]));
?>

<?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
<?php $box->beginActions() ?>
<?= $box->renderSearchButton() ?>
<?= $box->renderSorter([
    'attributes' => [
        'name', 'id', 'client', 'tariff',
        'panel', 'ip', 'state', 'expires'
    ],
]) ?>
<?= $box->renderPerPage() ?>

<?php $box->endActions() ?>

<?= $box->renderBulkActions([
    'items' => [
        ButtonDropdown::widget([
            'label'    => Yii::t('app', 'Lock'),
            'dropdown' => [
                'options' => ['class' => 'pull-right'],
                'items' => [
                    [
                        'label'       => Yii::t('app', 'Enable lock'),
                        'url'         => '#',
                        'linkOptions' => [
                            'class'          => 'bulk-action',
                            'data-attribute' => 'is_secured',
                            'data-value'     => '1',
                            'data-url'       => 'set-lock'
                        ]
                    ],
                    [
                        'label'       => Yii::t('app', 'Disable lock'),
                        'url'         => '#',
                        'linkOptions' => [
                            'class'          => 'bulk-action',
                            'data-attribute' => 'is_secured',
                            'data-value'     => '0',
                            'data-url'       => 'set-lock'
                        ]
                    ]
                ]
            ]
        ]),
    ]
]) ?>
<?= $box->renderSearchForm(compact('states')) ?>
<?php $box::end() ?>

<?php
$box->beginBulkForm();
print ServerGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $model,
    'osImages'     => $osimages,
    'columns'      => [
        'checkbox',
        'server',
        'client_id',
        'seller_id',
        'state',
        'expires',
        'discount',
        'actions',
    ]
]);
$box::endBulkForm();
?>

<?php
Pjax::end();
