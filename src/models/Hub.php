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

use hipanel\base\ModelTrait;
use hipanel\behaviors\TaggableBehavior;
use hipanel\models\TaggableInterface;
use hipanel\modules\finance\models\proxy\Resource;
use hipanel\modules\server\models\query\HubQuery;
use hipanel\modules\server\validators\MacValidator;
use hipanel\modules\stock\models\Part;
use hiqdev\hiart\ActiveQuery;
use Yii;

class Hub extends Device implements TaggableInterface
{
    use ModelTrait;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_OPTIONS = 'options';
    public const STATE_OK = 'ok';
    public const STATE_DISABLED = 'disabled';
    public const STATE_DELETED = 'deleted';

    public function behaviors()
    {
        return [
            TaggableBehavior::class,
        ];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                [
                    'id',
                    'access_id',
                    'type_id',
                    'server_type_id',
                    'state_id',
                    'buyer_id',
                    'last_buyer_id',
                    'units',
                    'tariff_id',
                    'client_id',
                    'last_client_id',
                ],
                'integer',
            ],
            [['tariff'], 'safe'],
            [
                [
                    'name',
                    'dc',
                    'mac',
                    'remoteid',
                    'note',
                    'description',
                    'ip',
                    'type_label',
                    'server_type_label',
                    'buyer',
                    'last_buyer',
                    'note',
                    'inn',
                    'model',
                    'community',
                    'traf_server_id',
                    'order_no',
                    'ports_num',
                    'traf_server_id',
                    'login',
                    'password',
                    'user_login',
                    'user_password',
                    'vlan_server_id',
                    'community',
                    'snmp_version_id',
                    'digit_capacity_id',
                    'nic_media',
                    'base_port_no',
                    'oob_key',
                    'traf_server_id_label',
                    'vlan_server_id_label',
                    'type',
                    'server_type',
                    'state',
                    'state_label',
                    'stat_device',
                    'stat_domain',
                    'rack',
                    'client',
                ],
                'string',
            ],
            [['name'], 'string', 'min' => 1, 'max' => 63],
            [['virtual', 'vxlan'], 'boolean'],
            [['vxlan'], 'default', 'value' => ""],

            [['tariff_id', 'sale_time'], 'required', 'on' => ['sale']],

            // Create and update
            [['type_id', 'name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [
                ['name', 'mac', 'ip'],
                'unique',
                'filter' => function ($query) {
                    $query->andWhere(['ne', 'id', $this->id]);
                },
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE],
            ],
            [['id'], 'integer', 'on' => self::SCENARIO_UPDATE],
            [['inn', 'mac', 'ip', 'model', 'order_no', 'note'], 'string', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['name', 'order_no'], 'filter', 'filter' => 'trim', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            // set Options
            [
                [
                    'id',
                    'inn',
                    'model',
                    'login',
                    'password',
                    'ports_num',
                    'community',
                    'snmp_version_id',
                    'digit_capacity_id',
                    'nic_media',
                    'base_port_no',
                    'base_port_no',
                    'user_login',
                    'user_password',
                ],
                'safe',
                'on' => 'options',
            ],
            [['traf_server_id', 'vlan_server_id'], 'integer', 'on' => self::SCENARIO_OPTIONS],

            [['ip'], 'ip', 'on' => ['create', 'update', 'options']],
            [['mac'], MacValidator::class],
            [['id'], 'required', 'on' => ['delete', 'restore', 'set-note', 'set-description']],
            [['note'], 'safe', 'on' => ['set-note']],
            // set description
            [['description'], 'string', 'on' => ['set-description']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type_id' => Yii::t('hipanel', 'Type'),
            'server_type_id' => Yii::t('hipanel:server:hub', 'Server type'),
            'tariff_id' => Yii::t('hipanel', 'Tariff'),
            'buyer_id' => Yii::t('hipanel:server:hub', 'Buyer'),
            'buyer' => Yii::t('hipanel:server:hub', 'Buyer'),
            'last_buyer_id' => Yii::t('hipanel:server:hub', 'Final buyer'),
            'last_buyer' => Yii::t('hipanel:server:hub', 'Final buyer'),
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
            'jbod' => Yii::t('hipanel:server', 'JBOD'),
            'location' => Yii::t('hipanel:server:hub', 'Location'),
            'name_ilike' => Yii::t('hipanel:server:hub', 'Switch'),
            'sale_time' => Yii::t('hipanel:server', 'Sale time'),
            'vxlan' => Yii::t('hipanel:server:hub', 'VXLAN'),
            'rack' => Yii::t('hipanel:server:hub', 'Rack'),
            'client' => Yii::t('hipanel:server:hub', 'Client'),
            'description' => Yii::t('hipanel:server:hub', 'CS notes'),
        ]);
    }

    public function getResources(): ActiveQuery
    {
        return $this->hasMany(Resource::class, ['object_id' => 'id']);
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
        return $this->hasMany(Part::class, ['dst_id' => 'id'])->limit(-1);
    }

    public function isDeleted(): bool
    {
        return isset($this->state) && $this->state === self::STATE_DELETED;
    }

    public function isVirtual(): bool
    {
        return $this->virtual;
    }

    public function isServer(): bool
    {
        return (bool)$this->server_type_id;
    }

    public function isVirtualServer(): bool
    {
        return $this->isVirtual() && $this->isServer();
    }

    public function getVxlanOptions(): array
    {
        return [
            '' => Yii::t('hipanel:server:hub', 'Not specified'),
            '0' => Yii::t('hipanel:server:hub', 'No'),
            '1' => Yii::t('hipanel:server:hub', 'Yes'),
        ];
    }
}
