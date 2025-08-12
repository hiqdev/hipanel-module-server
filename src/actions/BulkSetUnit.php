<?php

declare(strict_types=1);

namespace hipanel\modules\server\actions;

use hipanel\actions\SmartUpdateAction;
use hipanel\modules\server\models\HubHardwareSettings;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\models\query\HubQuery;
use Yii;
use yii\helpers\ArrayHelper;

class BulkSetUnit extends SmartUpdateAction
{
    public function beforeLoad(): void
    {
        $action = $this->controller->action;
        $action->collection->setModel(new Hub());
    }

    public function beforeFetch(): void
    {
        /** @var HubQuery $query */
        $query = $this->getDataProvider()->query;
        $query->withHardwareSettings();
    }

    public function beforeSave(): void
    {
        /** @var \hipanel\actions\Action $action */
        $action = $this->controller->action;
        $switches = Yii::$app->request->post('HardwareSettings');
        $units = ArrayHelper::remove($switches, 'units');
        foreach ($switches as $id => $switch) {
            $switches[$id]['units'] = $units;
        }
        $settingsModel = new HubHardwareSettings;
        $settingsModel->scenario = 'set-units';
        $action->collection->setModel($settingsModel);
        $action->collection->load($switches);
    }
}
