<?php

namespace hipanel\modules\server\widgets\combo;

use hipanel\widgets\RefCombo;
use Yii;

class ServerStateRefCombo extends RefCombo
{
    public function prepareData()
    {
        $refs = parent::prepareData();
        $user = Yii::$app->user;
        if ($user->can('support') || $user->can('role:junior-manager')) {
            return $refs;
        }

        return array_filter($refs, function ($k) {
            return in_array($k, ['setuping', 'error', 'ok']);
        }, ARRAY_FILTER_USE_KEY);
    }
}
