<?php

namespace hipanel\modules\server\widgets\combo;

use hipanel\widgets\RefCombo;
use Yii;

class ServerTypeRefCombo extends RefCombo
{
    /**
     * @return array
     */
    public function prepareData(): array
    {
        $refs = parent::prepareData();
        if (Yii::$app->user->can('ref.view.not-used')) {
            return $refs;
        }

        return array_filter($refs, static function (string $k): bool {
            return in_array($k, ['dedicated', 'unmanaged', 'setup', 'jbod', 'remote', 'vds', 'avds', 'ovds', 'svds', 'cdn', 'cdnpix', 'nic', 'uplink3'], true);
        }, ARRAY_FILTER_USE_KEY);
    }
}
