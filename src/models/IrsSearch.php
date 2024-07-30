<?php declare(strict_types=1);

namespace hipanel\modules\server\models;

use hipanel\base\SearchModelTrait;

class IrsSearch extends Irs
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public static function tableName(): string
    {
        return Server::tableName();
    }
}
