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

/**
 * Class TariffPageCalculation is designed to calculate servers' prices
 * when server is not created yet (using tariff only) and is not added to the cart.
 *
 * The class has dropped dependency on Cart module
 */
class TariffPageCalculation extends OrderCalculation
{
    /** {@inheritdoc} */
    public static function primaryKey()
    {
        return ['tariff_id'];
    }

    /** {@inheritdoc} */
    public function synchronize()
    {
        return true;
    }
}
