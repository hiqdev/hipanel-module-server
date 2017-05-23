<?php

namespace hipanel\modules\server\models;

use hipanel\base\SearchModelTrait;

class HubSearch extends Hub
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }
}
