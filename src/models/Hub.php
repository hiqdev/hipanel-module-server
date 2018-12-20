<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

use hipanel\modules\server\validators\MacValidator;
use Yii;

class Hub extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_OPTIONS = 'options';

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'access_id', 'type_id', 'state_id', 'buyer_id', 'units'], 'integer'],
            [[
                'name', 'dc', 'mac', 'remoteid', 'note', 'ip', 'type_label', 'buyer', 'note', 'inn', 'model',
                'community', 'login', 'traf_server_id', 'order_no', 'password', 'ports_num', 'traf_server_id',
                'vlan_server_id', 'community', 'snmp_version_id', 'digit_capacity_id', 'nic_media', 'base_port_no',
                'oob_key', 'traf_server_id_label', 'vlan_server_id_label', 'type',
            ], 'string'],
            [['virtual'], 'boolean'],

            // Create and update
            [['type_id', 'name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['id'], 'integer', 'on' => self::SCENARIO_UPDATE],
            [['inn', 'mac', 'ip', 'model', 'order_no', 'note'], 'string', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            // set Options
            [[
                'id', 'inn', 'model', 'login', 'password', 'ports_num', 'community',
                'snmp_version_id', 'digit_capacity_id', 'nic_media', 'base_port_no', 'base_port_no',
            ], 'safe', 'on' => 'options'],
            [['traf_server_id', 'vlan_server_id'], 'integer', 'on' => self::SCENARIO_OPTIONS],

            [['ip'], 'ip', 'on' => ['create', 'update', 'options']],
            [['mac'], MacValidator::class],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type_id' => Yii::t('hipanel', 'Type'),
            'buyer_id' => Yii::t('hipanel:server:hub', 'Buyer'),
            'inn' => Yii::t('hipanel:server:hub', 'INN'),
            'model' => Yii::t('hipanel:server:hub', 'Model'),
            'order_no' => Yii::t('hipanel:server:hub', 'Order No.'),
            'login' => Yii::t('hipanel:server:hub', 'Login'),
            'password' => Yii::t('hipanel:server:hub', 'Password'),
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
            'rack' => Yii::t('hipanel:server', 'Rack'),
            'ipmi' => Yii::t('hipanel:server', 'IPMI'),
            'name_ilike' => Yii::t('hipanel:server:hub', 'Switch'),
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

    public function getHardwareSettings()
    {
        return $this->hasOne(HardwareSettings::class, ['id' => 'id']);
    }
}
