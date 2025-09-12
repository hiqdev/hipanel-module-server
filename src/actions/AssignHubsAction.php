<?php

declare(strict_types=1);


namespace hipanel\modules\server\actions;

class AssignHubsAction extends AssignableHubs
{
    protected function collectFromRequest(): array
    {
        return $this->controller->request->post($this->collection->getModel()->formName());
    }
}
