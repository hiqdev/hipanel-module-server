<?php

namespace hipanel\modules\server\widgets\combo;

use hipanel\widgets\RefCombo;
use Yii;

class ServerTypeRefCombo extends RefCombo
{
    public function prepareData()
    {
        $refs = parent::prepareData();
        if (Yii::$app->user->can('ref.view.not-used')) {
            return $refs;
        }

        return array_filter($refs, function ($k) {
            return in_array($k, ['dedicated', 'unmanaged']);
        }, ARRAY_FILTER_USE_KEY);
    }
}
