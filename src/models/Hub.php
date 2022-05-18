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
use hipanel\modules\server\models\query\HubQuery;
use hipanel\modules\server\models\traits\AssignSwitchTrait;
use hipanel\modules\server\validators\MacValidator;
use hipanel\modules\stock\models\Part;
use hiqdev\hiart\ActiveQuery;
use Yii;

class Hub extends Model implements AssignSwitchInterface
{
    use ModelTrait, AssignSwitchTrait;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_OPTIONS = 'options';
    public const STATE_OK = 'ok';
    public const STATE_DELETED = 'deleted';


    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'access_id', 'type_id', 'state_id', 'buyer_id', 'units', 'tariff_id', 'client_id'], 'integer'],
            [['tariff'], 'safe'],
            [[
                'name', 'dc', 'mac', 'remoteid', 'note', 'ip', 'type_label', 'buyer', 'note', 'inn', 'model',
                'community', 'traf_server_id', 'order_no', 'ports_num', 'traf_server_id',
                'login', 'password', 'user_login', 'user_password',
                'vlan_server_id', 'community', 'snmp_version_id', 'digit_capacity_id', 'nic_media', 'base_port_no',
                'oob_key', 'traf_server_id_label', 'vlan_server_id_label', 'type', 'state', 'state_label',
            ], 'string'],
            [['virtual'], 'boolean'],

            [['tariff_id', 'sale_time'], 'required', 'on' => ['sale']],

            // Create and update
            [['type_id', 'name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['name', 'mac', 'ip'], 'unique', 'filter' => function ($query) {
                $query->andWhere(['ne', 'id', $this->id]);
            }, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['id'], 'integer', 'on' => self::SCENARIO_UPDATE],
            [['inn', 'mac', 'ip', 'model', 'order_no', 'note'], 'string', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            // set Options
            [[
                'id', 'inn', 'model', 'login', 'password', 'ports_num', 'community',
                'snmp_version_id', 'digit_capacity_id', 'nic_media', 'base_port_no', 'base_port_no',
                'user_login', 'user_password',
            ], 'safe', 'on' => 'options'],
            [['traf_server_id', 'vlan_server_id'], 'integer', 'on' => self::SCENARIO_OPTIONS],

            [['ip'], 'ip', 'on' => ['create', 'update', 'options']],
            [['mac'], MacValidator::class],
            [['id'], 'required', 'on' => ['delete', 'restore']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type_id' => Yii::t('hipanel', 'Type'),
            'tariff_id' => Yii::t('hipanel', 'Tariff'),
            'buyer_id' => Yii::t('hipanel:server:hub', 'Buyer'),
            'buyer' => Yii::t('hipanel:server:hub', 'Buyer'),
            'inn' => Yii::t('hipanel:server:hub', 'INN'),
            'inn_ilike' => Yii::t('hipanel:server:hub', 'INN'),
            'model' => Yii::t('hipanel:server:hub', 'Model'),
            'order_no' => Yii::t('hipanel:server:hub', 'Order No.'),
            'login' => Yii::t('hipanel:server:hub', 'Login'),
            'password' => Yii::t('hipanel:server:hub', 'Password'),
            'user_login' => Yii::t('hipanel:server:hub', 'User login'),
            'user_password' => Yii::t('hipanel:server:hub', 'User password'),
            'ports_num' => Yii::t('hipanel:server:hub', 'Number of ports'),
            'traf_server_id' => Yii::t('hipanel:server:hub', 'Server where traf is counted'),
            'vlan_server_id' => Yii::t('hipanel:server:hub', 'Server in same VLAN'),
            'community' => Yii::t('hipanel:server:hub', 'Community'),
            'snmp_version_id' => Yii::t('hipanel:server:hub', 'SNMP version'),
            'digit_capacity_id' => Yii::t('hipanel:server:hub', 'Digit capacity'),
            'nic_media' => Yii::t('hipanel:server:hub', 'NIC media'),
            'base_port_no' => Yii::t('hipanel:server:hub', 'Base port no'),
            'oob_key' => Yii::t('hipanel:server:hub', 'OOB license key'),
            'mac' => Yii::t('hipanel:server:hub', 'MAC address'),
            'name' => Yii::t('hipanel:server:hub', 'Name'),
            'note' => Yii::t('hipanel:server:hub', 'Note'),
            'net' => Yii::t('hipanel:server', 'Switch'),
            'kvm' => Yii::t('hipanel:server', 'KVM'),
            'pdu' => Yii::t('hipanel:server', 'APC'),
            'rack_like' => Yii::t('hipanel:server', 'Rack'),
            'ipmi' => Yii::t('hipanel:server', 'IPMI'),
            'location' => Yii::t('hipanel:server:hub', 'Location'),
            'name_ilike' => Yii::t('hipanel:server:hub', 'Switch'),
            'sale_time' => Yii::t('hipanel:server', 'Sale time'),
        ]);
    }

    public function getBindings()
    {
        return $this->hasMany(Binding::class, ['device_id' => 'id'])->indexBy('type');
    }

    public function getBinding($type)
    {
        if (!isset($this->bindings[$type])) {
            return null;
        }

        return $this->bindings[$type];
    }

    public function getHardwareSettings(): ActiveQuery
    {
        return $this->hasOne(HardwareSettings::class, ['id' => 'id']);
    }

    public function getMonitoringSettings()
    {
        return $this->hasOne(MonitoringSettings::class, ['id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return HubQuery
     */
    public static function find($options = [])
    {
        return new HubQuery(get_called_class(), [
            'options' => $options,
        ]);
    }

    public function getParts()
    {
        return $this->hasMany(Part::class, ['dst_id' => 'id']);
    }

    public function isDeleted(): bool
    {
        return isset($this->state) && $this->state === self::STATE_DELETED;
    }
}
