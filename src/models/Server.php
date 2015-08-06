<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\models;

use Yii;
use yii\base\NotSupportedException;

class Server extends \hipanel\base\Model
{

    use \hipanel\base\ModelTrait;

    /**
     * @return array the list of attributes for this record
     */
//    public function attributes()
//    {
//        return [

//        ];
//    }

    public function rules()
    {
        return [
            [
                [
                    'id',
                    'name',
                    'seller',
                    'seller_id',
                    'client',
                    'client_id',
                    'panel',
                    'parent_tariff',
                    'tariff',
                    'tariff_note',
                    'discounts',
                    'request_state',
                    'request_state_label',
                    'state_label',
                    'status_time',
                    'sale_time',
                    'autorenewal',
                    'state',
                    'type',
                    'expires',
                    'block_reason_label',
                    'ip',
                    'ips',
                    'os',
                    'osimage',
                    'rcp',
                    'vnc',
                    'statuses',
                    'running_task',
                    'note'
                ],
                'safe'
            ],
            [
                ['state'],
                'isOperable',
                'on' => [
                    'reinstall',
                    'reboot',
                    'reset',
                    'shutdown',
                    'power-off',
                    'power-on',
                    'boot-live',
                    'regen-root-password'
                ]
            ],
            [['id', 'note'], 'required', 'on' => ['set-note']],
        ];
    }

//    public function scenarios () {
//        return [
//            'reinstall'           => ['id', 'osimage', 'panel'],
//            'boot-live'           => ['id', 'osimage'],
//            'reboot'              => ['id'],
//            'reset'               => ['id'],
//            'shutdown'            => ['id'],
//            'power-off'           => ['id'],
//            'power-on'            => ['id'],
//            'regen-root-password' => ['id'],
//        ];
//    }

    public function goodStates()
    {
        return ['ok', 'disabled'];
    }

    /**
     * @return bool
     */
    public function isOperable()
    {
        /// TODO: all is operable for admin
        if ($this->running_task || !in_array($this->state, $this->goodStates())) {
            return false;
        }
        return true;
    }

    /**
     * Returns true, if server supports VNC
     *
     * @return bool
     */
    public function isVNCSupported()
    {
        return $this->type != 'ovds';
    }

    public function isPwChangeSupported()
    {
        return $this->type == 'ovds';
    }

    public function isLiveCDSupported()
    {
        return $this->type != 'ovds';
    }

    /**
     * @return bool
     * @throws NotSupportedException
     */
    public function checkOperable()
    {
        if (!$this->isOperable()) {
            throw new NotSupportedException(\Yii::t('app', 'Server already has a running task. Can not start new.'));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function scenarioCommands()
    {
        return [
            'reinstall' => 'resetup'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'remoteid'            => Yii::t('app', 'Remote ID'),
            'name_like'           => Yii::t('app', 'Name'),
            'name'                => Yii::t('app', 'Name'),
            'panel'               => Yii::t('app', 'Panel'),
            'parent_tariff'       => Yii::t('app', 'Parent tariff'),
            'tariff'              => Yii::t('app', 'Tariff'),
            'tariff_note'         => Yii::t('app', 'Tariff note'),
            'discounts'           => Yii::t('app', 'Discounts'),
            'request_state'       => Yii::t('app', 'Request state'),
            'state_label'         => Yii::t('app', 'State'),
            'status_time'         => Yii::t('app', 'Last operation time'),
            'sale_time'           => Yii::t('app', 'Sale time'),
            'autorenewal'         => Yii::t('app', 'Autorenewal'),
            'expires'             => Yii::t('app', 'Expires'),
            'block_reason_label'  => Yii::t('app', 'Block reason label'),
            'request_state_label' => Yii::t('app', 'Request state label'),
            'ips'                 => Yii::t('app', 'IP addresses'),
        ]);
    }
}
