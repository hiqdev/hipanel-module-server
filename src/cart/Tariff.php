<?php

namespace hipanel\modules\server\cart;

use hipanel\modules\finance\models\CalculableModelInterface;

/**
 * Class Tariff represents tariff for server
 *
 * @package hipanel\modules\server\cart
 */
class Tariff extends \hipanel\modules\finance\models\Tariff implements CalculableModelInterface
{
    const TYPE_XEN = 'svds';
    const TYPE_OPENVZ = 'ovds';

    /**
     * Method creates and returns corresponding Calculation model
     *
     * @return OrderCalculation
     */
    public function getCalculationModel()
    {
        return new TariffPageCalculation([
            'tariff_id' => $this->id
        ]);
    }
}
