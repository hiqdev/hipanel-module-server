<?php

declare(strict_types=1);

namespace hipanel\modules\server\actions;

use hipanel\actions\Action;
use hipanel\actions\SmartUpdateAction;
use hipanel\modules\server\models\HardwareSettings;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\models\query\ServerQuery;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class BulkSetUnit extends SmartUpdateAction
{
    public function beforeLoad(): void
    {
        $action = $this->controller->action;
        $action->collection->setModel(new Hub());
    }

    public function beforeFetch(): void
    {
        /** @var ServerQuery $query */
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
        $settingsModel = new HardwareSettings;
        $settingsModel->scenario = 'set-units';
        $action->collection->setModel($settingsModel);
        $action->collection->load($switches);
    }
}
