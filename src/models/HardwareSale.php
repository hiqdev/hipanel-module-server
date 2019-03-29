<?php

namespace hipanel\modules\server\models;

/**
 * Class HardwareSale
 *
 * @property int $id
 * @property int $tariff_id
 * @property int $price_id
 * @property int $part_id
 * @property string $sale_time
 * @property string $part
 * @property string|null $serialno
 * @property string $usage_type
 * @property string $leasing_since
 * @property string $leasing_till
 * @property array|null $data
 */
class HardwareSale extends \hipanel\base\Model
{
    public const USAGE_TYPE_LEASING = 'leasing';
    public const USAGE_TYPE_RENT = 'rent';
    public const USAGE_TYPE_COLO = 'colo';

    public function rules()
    {
        return [
            [['id', 'tariff_id', 'price_id', 'part_id'], 'integer'],
            [['scenario', 'sale_time', 'serialno', 'part', 'usage_type', 'leasing_till', 'leasing_since'], 'string'],
            [['data'], 'safe'],
        ];
    }

    public function saleTime(): \DateTime
    {
        return new \DateTime($this->sale_time);
    }
}
