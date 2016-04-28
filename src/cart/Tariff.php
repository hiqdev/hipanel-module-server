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

use hipanel\modules\finance\models\CalculableModelInterface;

/**
 * Class Tariff represents tariff for server.
 */
class Tariff extends \hipanel\modules\finance\models\Tariff implements CalculableModelInterface
{
    const TYPE_XEN = 'svds';
    const TYPE_OPENVZ = 'ovds';

    /**
     * Method creates and returns corresponding Calculation model.
     *
     * @return OrderCalculation
     */
    public function getCalculationModel()
    {
        return new TariffPageCalculation([
            'tariff_id' => $this->id,
        ]);
    }
}
