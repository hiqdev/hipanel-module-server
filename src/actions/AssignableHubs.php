<?php

declare(strict_types=1);


namespace hipanel\modules\server\actions;

use hipanel\actions\Action;
use hipanel\actions\SmartUpdateAction;
use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\models\AssignHubsInterface;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\models\Server;
use hiqdev\hiart\Collection;
use yii\base\InvalidArgumentException;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read string $assignableClassName
 * @property-read mixed $assignableHubs
 */
abstract class AssignableHubs extends SmartUpdateAction
{
    private const array ASSIGNABLE_MAP = [
        'server' => Server::class,
        'hub' => Hub::class,
    ];

    abstract protected function collectFromRequest(): array;

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
                /** @var AssignHubsInterface $model */
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

    public function beforeSave(): void
    {
        /** @var AssignHubsForm $form */
        $form = $this->collection->getModel();
        $hubs = $this->collectFromRequest();
        foreach ($hubs as &$hub) {
            $model = clone $form;
            $model::setModelClass($this->getAssignableClassName());
            if ($model->load($hub, '') && $model->validate()) {
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

    public function beforeFetch(): void
    {
        $dataProvider = $this->getDataProvider();
        $dataProvider->query->withBindings()->select(['*']);
        parent::beforeFetch();
    }

    private function getAssignableClassName(): string
    {
        return self::ASSIGNABLE_MAP[$this->controller->id];
    }
}
