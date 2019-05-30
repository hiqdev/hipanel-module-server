<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */
namespace hipanel\modules\server\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;
use Yii;


class ConfigSearch extends Config
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'name_ilike',
            'subname_ilike',
            'cpu_ilike',
            'ram_ilike',
            'hdd_ilike',
            'ssd_ilike',
            'traffic_ilike',
            'lan_ilike',
            'raid_ilike',
            'descr_ilike',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name_ilike'        => Yii::t('hipanel:server', 'Name'),
            'label_ilike'     => Yii::t('hipanel:server:config', 'Subname'),
            'cpu_ilike'         => Yii::t('hipanel:server:config', 'CPU'),
            'ram_ilike'         => Yii::t('hipanel:server:config', 'RAM'),
            'hdd_ilike'         => Yii::t('hipanel:server:config', 'HDD'),
            'ssd_ilike'         => Yii::t('hipanel:server:config', 'HDD'),
            'traffic_ilike'     => Yii::t('hipanel:server:config', 'Traffic'),
            'lan_ilike'         => Yii::t('hipanel:server:config', 'LAN'),
            'raid_ilike'        => Yii::t('hipanel:server:config', 'RAID'),
            'descr_ilike' => Yii::t('hipanel:server:config', 'Description'),
        ]);
    }
}
