<?php

declare(strict_types=1);

namespace hipanel\modules\server\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hiqdev\hiart\ActiveQuery;

/**
 *
 * @property-read ActiveQuery $bindings
 */
class Device extends Model implements AssignSwitchInterface
{
    use ModelTrait;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id',], 'integer'],
            [['name', 'type'], 'string'],
        ]);
    }

    public function getBindings(): ActiveQuery
    {
        return $this->hasMany(Binding::class, ['device_id' => 'id'])->indexBy(function ($binding) {
            return $binding->typeWithNo;
        });
    }

    public function getBinding(string $type): ?Binding
    {
        return $this->bindings[$type] ?? null;
    }
}
