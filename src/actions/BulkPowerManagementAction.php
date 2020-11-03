<?php

namespace hipanel\modules\server\actions;

use hipanel\actions\Action;
use hipanel\actions\SmartUpdateAction;
use hipanel\modules\server\forms\PowerManagementForm;
use hiqdev\hiart\Collection;
use Yii;

class BulkPowerManagementAction extends SmartUpdateAction
{
    public function init(): void
    {
        $scenario = $this->id;
        $this->setCollection([
            'class' => Collection::class,
            'model' => new PowerManagementForm(),
            'scenario' => $scenario,
        ]);
        $this->data = static function (Action $action, array $data) use ($scenario): array {
            $form = new PowerManagementForm(['scenario' => $scenario]);
            $form->setServers($data['models']);

            return [
                'model' => $form,
                'models' => [$form],
                'scenario' => $scenario,
            ];
        };
        parent::init();
    }

    public function beforeSave()
    {
        /** @var Action $action */
        $action = $this->controller->action;
        $serverIds = Yii::$app->request->post('server_ids', []);
        $first = $action->collection->first;
        $models = [];
        foreach ($serverIds as $id) {
            $models[] = new PowerManagementForm([
                'id' => $id,
                'reason' => $first->reason,
                'scenario' => $first->scenario,
            ]);
        }
        $action->collection->set($models);
    }
}
