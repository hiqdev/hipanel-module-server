<?php

namespace hipanel\modules\server\forms;

use hipanel\modules\server\models\Config;

class ConfigForm extends Config
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'config';
    }
}
