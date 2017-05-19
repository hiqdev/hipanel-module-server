<?php

namespace hipanel\modules\server\models\query;

use hiqdev\hiart\ActiveQuery;

class BindingQuery extends ActiveQuery
{
    public function all($db = null)
    {
        $rows = $this->primaryModel->bindings;

        return $this->populate($rows);
    }
}
