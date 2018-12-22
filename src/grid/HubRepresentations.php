<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

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
            'sale' => [
                'label' => Yii::t('hipanel:server:hub', 'sale'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'switch',
                    'buyer',
                    'tariff',
                    'model',
                    'type',
                ],
            ],
        ]);
    }
}
