<?php

namespace hipanel\modules\server\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class HubRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'switch',
                    'inn',
                    'model',
                    'type',
                    'ip',
                    'mac',
                ],
            ],
        ]);
    }
}
