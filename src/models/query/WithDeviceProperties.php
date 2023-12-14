<?php

declare(strict_types=1);

namespace hipanel\modules\server\models\query;

trait WithDeviceProperties
{
    public function withDeviceProperties(): self
    {
        $this->joinWith(['deviceProperties']);
        $this->andWhere(['with_deviceProperties' => 1]);

        return $this;
    }
}
