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
use Yii;

class Config extends Model
{
    use ModelTrait;

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    const SCENARIO_DELETE = 'delete';

    const SCENARIO_ENABLE = 'enable';

    const SCENARIO_DISABLE = 'disable';

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
            [['server_ids', 'servers'], 'safe'],
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
            'server_ids' => Yii::t('hipanel:server:config', 'Servers'),
            'us_tariff_id' => Yii::t('hipanel:server:config', 'USA tariff'),
            'nl_tariff_id' => Yii::t('hipanel:server:config', 'Netherlands tariff'),
            'us_tariff' => Yii::t('hipanel:server:config', 'USA tariff'),
            'nl_tariff' => Yii::t('hipanel:server:config', 'Netherlands tariff'),
            'label' => Yii::t('hipanel:server:config', 'Subname'),
            'cpu' => Yii::t('hipanel:server:config', 'CPU'),
            'ram' => Yii::t('hipanel:server:config', 'RAM'),
            'hdd' => Yii::t('hipanel:server:config', 'HDD'),
            'ssd' => Yii::t('hipanel:server:config', 'SSD'),
            'lan' => Yii::t('hipanel:server:config', 'LAN'),
            'raid' => Yii::t('hipanel:server:config', 'RAID'),
            'sort_order' => Yii::t('hipanel:server:config', 'Sort order'),
        ]);
    }
}
