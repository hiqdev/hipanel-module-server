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

class HubSearch extends Hub
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public static function tableName()
    {
        return Hub::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'with_bindings',
            'with_servers',
            'name_inilike',
            'rack_ilike',
            'rack_inilike',
            'order_no_ilike',
            'state_in',
            'tags',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name_inilike' => Yii::t('hipanel:server', 'Switch'),
            'rack_inilike' => Yii::t('hipanel:server', 'List of racks'),
            'rack_ilike' => Yii::t('hipanel:server', 'Rack'),
            'order_no_ilike' => Yii::t('hipanel:server:hub', 'Order No.'),
            'state_in' => Yii::t('hipanel:server:hub', 'State'),
            'ip_ilike' => Yii::t('hipanel:server', 'IP'),
            'mac_ilike' => Yii::t('hipanel:server:hub', 'MAC address'),
            'model_ilike' => Yii::t('hipanel:server:hub', 'Model'),
            'tariff_ilike' => Yii::t('hipanel', 'Tariff'),
        ]);
    }

    public function getStateOptions(): array
    {
        return [
            self::STATE_OK => Yii::t('hipanel:server:hub', 'OK'),
            self::STATE_DELETED => Yii::t('hipanel:server:hub', 'Deleted'),
            self::STATE_DISABLED => Yii::t('hipanel:server:hub', 'Disabled'),
        ];
    }
}
