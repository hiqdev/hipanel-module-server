<?php

declare(strict_types=1);

namespace hipanel\modules\server\models;

class HubHardwareSettings extends HardwareSettings
{
    public static function tableName()
    {
        return 'hub';
    }
}
