<?php

/*
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\grid;

use hipanel\modules\server\helpers\ServerHelper;
use hipanel\modules\server\widgets\OSFormatter;
use Yii;

class ChangeGridView extends \hipanel\modules\finance\grid\ChangeGridView
{
    public static function defaultColumns()
    {
        return array_merge(parent::defaultColumns(), [
            'user_comment' => [
                'value' => function ($model) {
                    return $model->user_comment . ': ' . $model->params['purpose'];
                }
            ],
            'tech_details' => [
                'format' => 'raw',
                'label' => Yii::t('hipanel/finance/change', 'Operation details'),
                'value' => function ($model) {
                    $params = $model->params;
                    return OSFormatter::widget([
                        'osimages' => ServerHelper::getOsimages($params['tariff_type']),
                        'imageName' => $params['osimage'],
                        'infoCircle' => false,
                    ]);
                }
            ],
        ]);
    }
}
