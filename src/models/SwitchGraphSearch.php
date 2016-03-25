<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\models;

use Yii;
use hipanel\base\SearchModelTrait;


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
     * @inheritdoc
     */
    public function searchAttributes()
    {
        return ([
            'id',
            'width',
        ]);
    }


    public function attributeLabels()
    {
        return [
            'width' => Yii::t('hipanel/server/rrd', 'Width (px)'),
        ];
    }
}
