<?php

namespace hipanel\modules\server\models;

use Yii;

/**
 * Class OpenvzPackage
 * @property string $name
 */
class OpenvzPackage extends Package
{
    protected function getResourceValue_cpu()
    {
        $part = $this->getPartByType('cpu');
        preg_match('/((\d+) MHz)$/i', $part->partno, $matches);
        return Yii::t('hipanel/server', '{0, number} MHz', $matches[2]);
    }

    protected function getResourceTitle_hdd()
    {
        return Yii::t('hipanel/server', 'HDD + SSD cache');
    }

    /** @inheritdoc */
    public function getLocations()
    {
        return [
            3 => Yii::t('hipanel/server', 'Netherlands, Amsterdam'),
            2 => Yii::t('hipanel/server', 'USA, Ashburn'),
        ];
    }
}
