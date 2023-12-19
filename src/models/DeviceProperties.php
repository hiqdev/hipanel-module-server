<?php

declare(strict_types=1);

namespace hipanel\modules\server\models;

use hipanel\base\Model;
use Yii;

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
            [['average_power_consumption'], 'number', 'on' => ['set-properties']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'average_power_consumption' => Yii::t('hipanel:server', 'Average Power Consumption'),
        ];
    }
}
