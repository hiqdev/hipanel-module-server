<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */
namespace hipanel\modules\server\models;

use hipanel\base\SearchModelTrait;


class ConfigSearch extends Config
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }
}
