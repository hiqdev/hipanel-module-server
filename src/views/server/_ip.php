<?php use hipanel\helpers\Url;
use hipanel\widgets\Box;
use yii\helpers\Html;

if (Yii::getAlias('@ip', false)) { ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            $ipsDataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $model->ips,
                'pagination' => false,
                'sort' => false,
            ]);

            $box = Box::begin(['renderBody' => false]);
            $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel/server', 'IP addresses'));
                if (Yii::$app->user->can('support')) {
                    $box->beginTools();
                    echo Html::a(
                        Yii::t('hipanel/server', 'Manage IP addresses'),
                        ['@ip', 'IpSearch' => ['server_in' => $model->name]],
                        ['class' => 'btn btn-default btn-sm']
                    );
                    $box->endTools();
                }
            $box->endHeader();
            $box->beginBody();
                echo \hipanel\grid\GridView::widget([
                    'dataProvider' => $ipsDataProvider,
                    'summary' => false,
                    'columns' => [
                        [
                            'attribute' => 'ip',
                            'format' => 'html',
                            'label' => Yii::t('hipanel', 'IP address'),
                            'options' => [
                                'style' => 'width: 20%',
                            ],
                            'value' => function ($model) {
                                if (Yii::$app->user->can('support') && Yii::getAlias('@ip', false) && $model->id) {
                                    return Html::a($model->ip, ['@ip/view', 'id' => $model->id]);
                                }

                                return $model->ip;
                            }
                        ],
                        [
                            'label' => Yii::t('hipanel', 'PTR'),
                            'attribute' => 'ptr',
                            'options' => [
                                'style' => 'width: 40%',
                            ],
                            'format' => 'raw',
                            'value' => function ($model) {
                                /** @var \hipanel\modules\hosting\models\Ip $model */
                                if ($model->canSetPtr()) {
                                    return \hipanel\widgets\XEditable::widget([
                                        'model' => $model,
                                        'attribute' => 'ptr',
                                        'pluginOptions' => [
                                            'url'       => Url::to('@ip/set-ptr')
                                        ],

                                    ]);
                                }

                                return null;
                            }
                        ],
                        [
                            'attribute' => 'links',
                            'format' => 'html',
                            'label' => Yii::t('hipanel/server', 'Services'),
                            'value' => function ($model) {
                                return \hipanel\widgets\ArraySpoiler::widget([
                                    'data' => $model->links,
                                    'formatter' => function ($link) {
                                        if (Yii::$app->user->can('support') && Yii::getAlias('@service', false)) {
                                            return Html::a($link->service, ['@service/view', 'id' => $link->service_id]);
                                        }

                                        return $link->service;
                                    }
                                ]);
                            }
                        ]
                    ]
                ]);
                $box->endBody();
            $box->end();
        ?>
        </div>
    </div>
<?php } ?>
