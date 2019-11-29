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

use DateTime;
use hipanel\base\Model;

/**
 * Class HardwareSale.
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
 * @property integer $sum
 * @property string $currency
 * @property float $quantity
 * @property array|null $data
 */
class HardwareSale extends Model
{
    public const USAGE_TYPE_LEASING = 'leasing';

    public const USAGE_TYPE_RENT = 'rent';

    public const USAGE_TYPE_COLO = 'colo';

    public function rules(): array
    {
        return [
            [['id', 'tariff_id', 'price_id', 'part_id', 'sum'], 'integer'],
            [['scenario', 'sale_time', 'serialno', 'part', 'usage_type', 'leasing_till', 'leasing_since', 'currency'], 'string'],
            [['quantity'], 'number'],
            [['data'], 'safe'],
        ];
    }

    public function saleTime(): DateTime
    {
        return new DateTime($this->sale_time);
    }
}
