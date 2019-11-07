<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server;

use Yii;

class Module extends \hipanel\base\Module
{
    /**
     * @var bool Whether server order is allowed
     */
    public $orderIsAllowed = true;

    public function isRedirectOutsideUrlExists(): bool
    {
        return (bool)Yii::$app->params['module.server.order.redirect.url'];
    }
}
