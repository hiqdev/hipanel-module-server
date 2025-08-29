<?php

declare(strict_types=1);


namespace hipanel\modules\server\actions;

use yii\helpers\ArrayHelper;

class BulkSetRackNoAction extends AssignableHubs
{
    protected function collectFromRequest(): array
    {
        $hubs = $this->controller->request->post($this->collection->getModel()->formName());
        $rackId = ArrayHelper::remove($hubs, 'rack_id');
        $rackPort = ArrayHelper::remove($hubs, 'rack_port');
        foreach ($hubs as $id => $model) {
            $hubs[$id]['rack_id'] = $rackId;
            $hubs[$id]['rack_port'] = $rackPort;
        }

        return $hubs;
    }
}
