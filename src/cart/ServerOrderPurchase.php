<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

use yii\base\InvalidConfigException;

/**
 * Class ServerOrderPurchase
 * @package hipanel\modules\server\cart
 */
class ServerOrderPurchase extends AbstractServerPurchase
{
    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Buy';
    }
    public function rules()
    {
        throw new InvalidConfigException('Server purchasing is not implemented yet. '); // todo
        return array_merge(parent::rules(), [
            [['expires'], 'required'],
        ]);
    }
}
