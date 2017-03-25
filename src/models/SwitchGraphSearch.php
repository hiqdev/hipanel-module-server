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

class SwitchGraphSearch extends SwitchGraph
{
    use SearchModelTrait {
        searchAttributes as private;
        rules as private;
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'width'], 'integer'],
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
            'width',
        ];
    }

    public function attributeLabels()
    {
        return [
            'width' => Yii::t('hipanel:server:rrd', 'Width (px)'),
        ];
    }
}
