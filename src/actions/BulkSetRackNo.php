<?php

namespace hipanel\modules\server\actions;

use hipanel\actions\Action;
use hipanel\actions\SmartUpdateAction;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class BulkSetRackNo extends SmartUpdateAction
{
    public function init(): void
    {
        $this->data = function (Action $action, array $data): array {
            $result = [];
            foreach ($data['models'] as $model) {
                $result['models'][] = $this->collection->getModel()::fromOriginalModel($model);
            }
            if (empty($result['models'])) {
                throw new NotFoundHttpException('There are no entries available for the selected operation. The type of selected records may not be suitable for the selected operation.');
            }
            $result['model'] = reset($result['models']);

            return $result;
        };
        parent::init();
    }

    public function beforeFetch()
    {
        $dataProvider = $this->getDataProvider();
        $dataProvider->query->withBindings()->select(['*']);
    }

    public function beforeSave()
    {
        /** @var \hipanel\actions\Action $action */
        $action = $this->controller->action;
        $formName = $this->collection->getModel()->formName();
        $models = Yii::$app->request->post($formName);
        $rackId = ArrayHelper::remove($models, 'rack_id');
        $rackPort = ArrayHelper::remove($models, 'rack_port');
        foreach ($models as $id => $model) {
            $models[$id]['rack_id'] = $rackId;
            $models[$id]['rack_port'] = $rackPort;
        }
        $action->collection->load($models);
    }
}
