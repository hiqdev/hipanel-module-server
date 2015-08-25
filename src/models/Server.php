<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\models;

use hipanel\validators\EidValidator;
use hipanel\validators\RefValidator;
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
            [['id'], 'integer'],
            [['osimage'], EidValidator::className()],
            [['panel'], RefValidator::className()],
            [
                [
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
                    'regen-root-password',
                    'reset-password',
                ]
            ],
            [['id', 'note'], 'required', 'on' => ['set-note']],
            [['id'], 'required', 'on' => ['enable-vnc']],
            [['id', 'osimage', 'panel'], 'required', 'on' => ['reinstall']],
            [['id', 'osimage'], 'required', 'on' => ['boot-live']],
        ];
    }

    /**
     * Determine good server states
     *
     * @return array
     */
    public function goodStates()
    {
        return ['ok', 'disabled'];
    }

    /**
     * @return bool
     */
    public function isOperable()
    {
        if ($this->running_task || !in_array($this->state, $this->goodStates())) {
            return false;
        }
        return true;
    }

    /**
     * Checks whether server supports VNC
     *
     * @return bool
     */
    public function isVNCSupported()
    {
        return $this->type != 'ovds';
    }

    /**
     * Checks whether server supports root password change
     *
     * @return bool
     */
    public function isPwChangeSupported()
    {
        return $this->type == 'ovds';
    }

    /**
     * Checks whether server supports LiveCD
     *
     * @return bool
     */
    public function isLiveCDSupported()
    {
        return $this->type != 'ovds';
    }

    /**
     * Checks whether server can be operated not
     *
     * @return bool
     * @throws NotSupportedException
     * @see isOperable()
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
            'reinstall' => 'resetup',
            'reset-password' => 'regenRootPassword',
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
