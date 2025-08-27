<?php

declare(strict_types=1);


namespace hipanel\modules\server\actions;

use hipanel\actions\Action;
use hipanel\actions\SmartUpdateAction;
use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\models\AssignSwitchInterface;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\models\Server;
use hiqdev\hiart\Collection;
use yii\base\InvalidArgumentException;
use yii\web\NotFoundHttpException;

class AssignHubsAction extends SmartUpdateAction
{
    public function init(): void
    {
        $this->collection = [
            'class' => Collection::class,
            'model' => new AssignHubsForm(),
            'scenario' => 'assign-hubs',
        ];
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

    public function beforeSave(): void
    {
        $baseModelMap = [
            'server' => Server::class,
            'hub' => Hub::class,
        ];
        /** @var AssignHubsForm $form */
        $form = $this->collection->getModel();
        $hubs = $this->controller->request->post($form->formName());
        foreach ($hubs as &$hub) {
            $model = clone $form;
            if ($model->load($hub, '') && $model->validate()) {
                $model::setModelClass($baseModelMap[$this->controller->id]);
                foreach ($model->toArray() as $key => $value) {
                    if (str_contains($key, '_')) {
                        $hub['hubs'][$key] = $value;
                        unset($hub[$key]);
                    }
                }
            } else {
                throw new InvalidArgumentException(implode(', ', $model->getFirstErrors()));
            }
        }

        $this->collection->load($hubs);
        parent::beforeSave();
    }
}
