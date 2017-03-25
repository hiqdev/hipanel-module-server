<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

use hipanel\base\SearchModelTrait;
use Yii;

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
     * {@inheritdoc}
     */
    public function searchAttributes()
    {
        return [
            'id',
            'period',
            'width',
            'shift',
            'graph',
        ];
    }

    public function attributeLabels()
    {
        return [
            'graph'  => Yii::t('hipanel:server:rrd', 'Graph'),
            'period' => Yii::t('hipanel:server:rrd', 'Precision'),
            'shift'  => Yii::t('hipanel:server:rrd', 'Shift (minutes)'),
            'width'  => Yii::t('hipanel:server:rrd', 'Width (px)'),
        ];
    }
}
