<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

/**
 * Abstract class AbstractServerPurchase.
 */
abstract class AbstractServerPurchase extends \hipanel\modules\finance\cart\AbstractPurchase
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public static function tableName()
    {
        return 'server';
    }
}
