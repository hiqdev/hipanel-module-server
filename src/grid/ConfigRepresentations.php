<?php

namespace hipanel\modules\server\grid;

use Yii;
use hiqdev\higrid\representations\RepresentationCollection;

class ConfigRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox', 'actions',
                    'name', 'client',
                    'config', 'tariffs', 'cc_servers',
                ],
            ],
        ]);
    }
}
