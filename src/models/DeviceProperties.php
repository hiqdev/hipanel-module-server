<?php

declare(strict_types=1);

namespace hipanel\modules\server\models;

use hipanel\base\Model;

class DeviceProperties extends Model
{
    public static function tableName(): string
    {
        return 'device';
    }

    public function scenarioActions(): array
    {
        return [
            'set-properties' => 'set-properties',
        ];
    }

    public function rules(): array
    {
        return [
            [['id'], 'integer', 'on' => ['set-properties']],
            [['average_power_consumption'], 'string', 'on' => ['set-properties']],
        ];
    }
}
