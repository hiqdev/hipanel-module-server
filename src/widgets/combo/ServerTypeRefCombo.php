<?php

namespace hipanel\modules\server\widgets\combo;

use hipanel\modules\server\helpers\ServerHelper;
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
        $usedTypes = ServerHelper::getUsedTypes();
        $allowedTypes = ['dedicated', 'unmanaged', 'setup', 'jbod', 'remote', 'vds', 'avds', 'ovds', 'svds', 'cdn', 'cdnpix', 'nic', 'uplink3'];
        if (empty($usedTypes)) {
            $usedTypes = $allowedTypes;
        }

        return array_filter($refs, static function (string $k) use ($usedTypes): bool {
            return in_array($k, $usedTypes, true);
        }, ARRAY_FILTER_USE_KEY);
    }
}
