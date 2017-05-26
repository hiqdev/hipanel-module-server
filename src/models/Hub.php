<?php

namespace hipanel\modules\server\models;

use Yii;

class Hub extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'access_id', 'type_id', 'state_id', 'buyer_id', 'units'], 'integer'],
            [[
                'name', 'dc', 'mac', 'remoteid', 'note', 'ip', 'type_label', 'buyer', 'note', 'inn', 'model',
                'community', 'login', 'traf_server_id', 'order_no', 'password', 'ports_num', 'traf_server_id',
                'vlan_server_id', 'community', 'snmp_version_id', 'digit_capacity_id', 'nic_media', 'base_port_no',
                'oob_key',
            ], 'string'],
            [['virtual'], 'boolean'],

            [['type_id', 'name'], 'required', 'on' => ['create', 'update']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type_id' => Yii::t('hipanel', 'Type'),
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
        ]);
    }
}
