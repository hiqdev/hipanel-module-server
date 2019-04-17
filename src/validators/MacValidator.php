<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\validators;

use yii\validators\RegularExpressionValidator;

class MacValidator extends RegularExpressionValidator
{
    /**
     * @var string Regexp-pattern to validate MAC address
     */
    public $pattern = '/^([0-9A-Fa-f]{2}[\.\s:-]){5}([0-9A-Fa-f]{2})$/';
}
