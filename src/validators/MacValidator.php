<?php

namespace hipanel\modules\server\validators;

use yii\validators\RegularExpressionValidator;

class MacValidator extends RegularExpressionValidator
{
    /**
     * @var string Regexp-pattern to validate MAC address
     */
    public $pattern = '/^([0-9a-f]{1,2}[\.:-\s]){5}([0-9a-f]{1,2})$/i';
}
