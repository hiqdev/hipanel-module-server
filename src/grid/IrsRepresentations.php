<?php

declare(strict_types=1);

namespace hipanel\modules\server\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class IrsRepresentations extends RepresentationCollection
{
    protected function fillRepresentations(): void
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'location',
                    'server',
                    'price',
                    'os',
                    'ip',
                    'administration',
                    'vxlan',
                    'delivery',
                    'actions'
                ],
            ],
        ]);
    }
}
