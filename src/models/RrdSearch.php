<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\models;

use Yii;
use hipanel\base\SearchModelTrait;


class RrdSearch extends Rrd
{
    use SearchModelTrait {
        searchAttributes as private;
        rules as private;
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'width', 'shift'], 'integer'],
            [['period'], 'number'],
            [['graph'], 'safe'],
        ];
    }

    public function formName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function searchAttributes()
    {
        return ([
            'id',
            'period',
            'width',
            'shift',
            'graph',
        ]);
    }


    public function attributeLabels()
    {
        return [
            'graph' => Yii::t('hipanel/server/rrd', 'Graph'),
            'period' => Yii::t('hipanel/server/rrd', 'Precision (min/px)'),
            'shift' => Yii::t('hipanel/server/rrd', 'Shift (minutes)'),
            'width' => Yii::t('hipanel/server/rrd', 'Width (px)'),
        ];
    }
}
