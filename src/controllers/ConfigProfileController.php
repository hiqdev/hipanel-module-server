<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\ComboSearchAction;
use hipanel\actions\SearchAction;
use hipanel\base\CrudController;
use yii\base\Event;

class ConfigProfileController extends CrudController
{
    /** {@inheritdoc} */
    public function actions()
    {
        return array_merge(parent::actions(), [
            'search' => [
                'class' => ComboSearchAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->andWhere(['hide_default' => true]);
                },
            ],
        ]);
    }
}
