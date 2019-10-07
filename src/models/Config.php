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

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\modules\server\models\query\ConfigQuery;
use Yii;
use yii\helpers\ArrayHelper;

class Config extends Model
{
    use ModelTrait;

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    const SCENARIO_DELETE = 'delete';

    const SCENARIO_ENABLE = 'enable';

    const SCENARIO_DISABLE = 'disable';

    const LOCATION_NL = 'nl';

    const LOCATION_US = 'us';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [[
                'id', 'client_id', 'seller_id', 'type_id', 'state_id',
                'us_tariff_id', 'nl_tariff_id', 'servers_num',
            ], 'integer'],
            [['server_ids', 'servers', 'profile_ids', 'profiles'], 'safe'],
            [['nl_old_price', 'us_old_price'], 'number', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['nl_low_limit', 'us_low_limit'], 'integer', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['nl_server_ids', 'nl_servers', 'us_server_ids', 'us_servers'], 'safe'],
            [['name', 'client', 'seller', 'state', 'state_label', 'type', 'type_label'], 'string'],
            [['sort_order'], 'integer', 'min' => 0],
            [
                [
                    'data',
                    'name',
                    'label',
                    'us_tariff',
                    'nl_tariff',
                    'cpu',
                    'ram',
                    'hdd',
                    'ssd',
                    'traffic',
                    'lan',
                    'raid',
                    'descr',
                ], 'string'],
            [
                ['client_id', 'name', 'label', 'cpu', 'ram'],
                'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
            ],

            ['id', 'required', 'on' => [
                self::SCENARIO_DELETE,
                self::SCENARIO_ENABLE,
                self::SCENARIO_DISABLE,
            ]],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'servers' => Yii::t('hipanel:server:config', 'All servers'),
            'server_ids' => Yii::t('hipanel:server:config', 'Servers'),
            'profile_ids' => Yii::t('hipanel:server:config', 'Profiles'),
            'us_tariff_id' => Yii::t('hipanel:server:config', 'US tariff'),
            'nl_tariff_id' => Yii::t('hipanel:server:config', 'NL tariff'),
            'us_old_price' => Yii::t('hipanel:server:config', 'US old price'),
            'nl_old_price' => Yii::t('hipanel:server:config', 'NL old price'),
            'us_tariff' => Yii::t('hipanel:server:config', 'US tariff'),
            'nl_tariff' => Yii::t('hipanel:server:config', 'NL tariff'),
            'us_servers_id' => Yii::t('hipanel:server:config', 'US servers'),
            'nl_servers_id' => Yii::t('hipanel:server:config', 'NL servers'),
            'us_servers' => Yii::t('hipanel:server:config', 'US servers'),
            'nl_servers' => Yii::t('hipanel:server:config', 'NL servers'),
            'us_low_limit' => Yii::t('hipanel:server:config', 'US low limit'),
            'nl_low_limit' => Yii::t('hipanel:server:config', 'NL low limit'),
            'label' => Yii::t('hipanel:server:config', 'Subname'),
            'cpu' => Yii::t('hipanel:server:config', 'CPU'),
            'ram' => Yii::t('hipanel:server:config', 'RAM'),
            'hdd' => Yii::t('hipanel:server:config', 'HDD'),
            'ssd' => Yii::t('hipanel:server:config', 'SSD'),
            'lan' => Yii::t('hipanel:server:config', 'Ethernet'),
            'raid' => Yii::t('hipanel:server:config', 'RAID'),
            'sort_order' => Yii::t('hipanel:server:config', 'Sort order'),
            'state_label' => Yii::t('hipanel', 'State'),
        ]);
    }

    public function getPrices()
    {
        return $this->hasMany(ConfigPrice::class, ['config_id' => 'id'])->indexBy('location');
    }

    /**
     * {@inheritdoc}
     * @return ConfigQuery
     */
    public static function find($options = [])
    {
        return new ConfigQuery(get_called_class(), [
            'options' => $options,
        ]);
    }

    public function getProfileOptions(): array
    {
        $profiles = Yii::$app->get('cache')->getOrSet([__METHOD__, Yii::$app->user->identity->id], function (): array {
            return ConfigProfile::find()->where(['class' => 'config', 'hide_default' => true])->limit(-1)->all();
        }, 1800); // 30 min

        return ArrayHelper::map($profiles, 'id', 'name');
    }
}
