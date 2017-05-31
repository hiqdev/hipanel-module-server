<?php

namespace hipanel\modules\server\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;

class HubSearch extends Hub
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
            'with_bindings',
        ]);
    }
}
