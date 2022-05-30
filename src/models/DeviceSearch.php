<?php
declare(strict_types=1);

namespace hipanel\modules\server\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;

class DeviceSearch extends Device
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    /**
     * {@inheritdoc}
     */
    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'name_like',
        ]);
    }
}
