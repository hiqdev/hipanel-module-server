<?php declare(strict_types=1);

namespace hipanel\modules\server\models;

use hipanel\base\ModelTrait;
use hipanel\modules\finance\models\Sale;
use hipanel\modules\server\models\query\IrsQuery;
use Yii;

/**
 *
 * @property-read string $locationName
 * @property-read string $administrationLabel
 * @property-read int $ipCount
 * @property-read null|Sale $lastSale
 */
class Irs extends Server
{
    use ModelTrait;

    public static function tableName()
    {
        return 'irs';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['irsOptions', 'actualSale'], 'safe'],
        ]);
    }

    public static function find($options = []): IrsQuery
    {
        return new IrsQuery(static::class);
    }

    public function getActualSale(): ?Sale
    {
        $sale = new Sale();
        $sale->setAttributes($this->actualSale);

        return $sale;

    }

    public function getLocationName(): string
    {
        if ($this->isRelationPopulated('bindings') && $rack = $this->getBinding('rack')) {
            return match (true) {
                str_starts_with($rack->switch, 'NL:AMS:DR:AMS17') => 'AMS17 (NL)',
                str_starts_with($rack->switch, 'NL:AMS:DR:AMS3') => 'AMS3 (NL)',
                str_starts_with($rack->switch, 'NL:AMS:EQ:AM7') => 'AM7 (NL)',
                str_starts_with($rack->switch, 'USA:ASH:EQ:DC10') => 'DC10 (USA)',
                str_starts_with($rack->switch, 'CN:EQ:HK1') => 'HK1 (HK)',
                default => $rack->switch
            };
        }

        return '';
    }

    public function getIpCount(): int
    {
        $count = 0;
        foreach ($this->irsOptions['prices']['server'] ?? [] as $row) {
            if ($row['type'] === 'overuse,ip_num') {
                $count = (int)$row['quantity'] ?? 0;
            }
        }

        return $count;
    }

    public function getAdministrationLabel(): string
    {
        $price = 0;
        foreach ($this->irsOptions['prices']['administration'] ?? [] as $row) {
            if ($row['type'] === 'monthly,support_time') {
                $price = (int)$row['price'];
            }
        }

        return match ($price) {
            50 => Yii::t('hipanel.server.irs', 'Standard Managed'),
            100 => Yii::t('hipanel.server.irs', 'Advanced Managed'),
            default => Yii::t('hipanel.server.irs', 'Unmanaged')
        };
    }
}
