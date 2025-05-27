<?php

declare(strict_types=1);
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server;

class Module extends \hipanel\base\Module
{
    /**
     * Whether server order is allowed
     */
    public bool $orderIsAllowed = true;

    public function __construct($id, $parent, $config = [])
    {
        parent::__construct($id, $parent, $config);
    }
}
