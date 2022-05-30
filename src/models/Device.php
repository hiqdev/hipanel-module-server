<?php
declare(strict_types=1);

namespace hipanel\modules\server\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;

class Device extends Model
{
    use ModelTrait;

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id',], 'integer'],
            [['name',], 'string'],
        ]);
    }

}
