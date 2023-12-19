<?php

declare(strict_types=1);

namespace hipanel\modules\server\forms;

use yii\base\Model;

class DeviceRangeForm extends Model
{
    public ?string $range = null;

    public function rules(): array
    {
        return [
            ['range', 'required'],
            ['range', 'match', 'pattern' => '/([A-Z]+)(\d+)-(\1)?(\d+)/'],
        ];
    }
}
