<?php

use hipanel\base\View;
use hipanel\grid\CheckboxColumn;
use hipanel\grid\GridView;
use hipanel\modules\client\grid\ClientColumn;
use hipanel\modules\client\grid\SellerColumn;
use hipanel\modules\server\grid\ServerColumn;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\modules\server\models\OsimageSearch;
use hipanel\modules\server\widgets\DiscountFormatter;
use hipanel\modules\server\widgets\OSFormatter;
use hipanel\modules\server\widgets\StateFormatter;
use hipanel\widgets\ActionBox;
use hipanel\widgets\BulkButtons;
use hipanel\widgets\GridActionButton;
use hipanel\widgets\Pjax;
use hipanel\widgets\RequestState;
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

<?php $box = ActionBox::begin(['bulk' => true, 'options' => ['class' => 'box-info']]) ?>
<?php $box->beginActions() ?>
<?= Html::a(Yii::t('app', 'Advanced search'), '#', ['class' => 'btn btn-info search-button']) ?>
<?php $box->endActions() ?>

<?php $box->beginBulkActions() ?>
<?= BulkButtons::widget([
    'model' => Yii::$app->controller->newModel(),
    'items' => [
        ButtonDropdown::widget([
            'label'    => Yii::t('app', 'Lock'),
            'dropdown' => [
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
    ],
]) ?>

<?php $box->endBulkActions() ?>
<?//= $this->render('_search', compact('model')) ?>
<?php $box::end() ?>

<?= ServerGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $model,
    'osImages'     => $osimages,
    'columns'      => [
        'server',
        'client_id',
        'seller_id',
        'state',
        'expires',
        'actions',
        'checkbox',
    ]
]); ?>
<?php
Pjax::end();
