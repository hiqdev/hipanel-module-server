<?php

declare(strict_types=1);


namespace hipanel\modules\server\actions;

use hipanel\actions\Action;
use hipanel\actions\SmartUpdateAction;
use hipanel\modules\server\models\AssignSwitchInterface;
use yii\web\NotFoundHttpException;

class AssignHubsAction extends SmartUpdateAction
{
    public function init(): void
    {
        $this->data = function (Action $action, array $data): array {
            $result = [];
            foreach ($data['models'] as $model) {
                /** @var AssignSwitchInterface $model */
                $collectionModel = $this->collection->getModel();
                $result['models'][] = $collectionModel::fromOriginalModel($model);
            }
            if (empty($result['models'])) {
                throw new NotFoundHttpException(
                    'There are no entries available for the selected operation. The type of selected records may not be suitable for the selected operation.'
                );
            }
            $result['model'] = reset($result['models']);

            return $result;
        };
        parent::init();
    }

    public function beforeFetch(): void
    {
        $dataProvider = $this->getDataProvider();
        $dataProvider->query->withBindings()->select(['*']);
        parent::beforeFetch();
    }
}
