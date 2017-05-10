<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

use hipanel\modules\hosting\models\Ip;
use hipanel\modules\server\helpers\ServerHelper;
use hipanel\validators\EidValidator;
use hipanel\validators\RefValidator;
use Yii;
use yii\base\NotSupportedException;

class Server extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    const STATE_OK = 'ok';
    const STATE_DISABLED = 'disabled';
    const STATE_BLOCKED = 'blocked';
    const STATE_DELETED = 'deleted';

    const VIRTUAL_DEVICES = ['avds', 'svds', 'ovds'];

    const SVDS_TYPES = ['avds', 'svds'];

    public function rules()
    {
        return [
            [['id', 'tariff_id', 'client_id', 'seller_id'], 'integer'],
            [['osimage'], EidValidator::class],
            [['panel'], RefValidator::class],
            [
                [
                    'name', 'dc',
                    'client', 'seller',
                    'os', 'panel', 'rcp',
                    'parent_tariff', 'tariff', 'tariff_note', 'discounts',
                    'request_state', 'request_state_label',
                    'autorenewal',
                    'type', 'type_label', 'state', 'state_label', 'statuses',
                    'block_reason_label',
                    'running_task',
                    'ip', 'ips_num', 'mac',
                    'acs_num', 'del_acs_num', 'wizzarded',
                    'vnc',
                    'note', 'label', 'hwsummary',
                ],
                'safe',
            ],
            [['switches', 'rack', 'net', 'kvm', 'pdu', 'ipmi'], 'safe'],
            [['last_expires', 'expires', 'status_time', 'sale_time'], 'date'],
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
                ],
            ],
            [
                ['id'],
                'required',
                'on' => [
                    'refuse', 'delete', 'enable-autorenewal',
                    'enable-vnc', 'set-note', 'set-label',
                    'enable-block', 'disable-block',
                ],
            ],
            [
                ['id'],
                'integer',
                'on' => [
                    'refuse', 'delete', 'enable-autorenewal',
                    'enable-vnc', 'set-note', 'set-label',
                    'enable-block', 'disable-block',
                ],
            ],
            [['id', 'osimage'], 'required', 'on' => ['reinstall']],
            [['id', 'osimage'], 'required', 'on' => ['boot-live']],
            [['type', 'comment'], 'required', 'on' => ['enable-block']],
            [['comment'], 'safe', 'on' => ['disable-block']],
        ];
    }

    /**
     * Determine good server states.
     *
     * @return array
     */
    public function goodStates()
    {
        return [static::STATE_OK, static::STATE_DISABLED];
    }

    /**
     * @return bool
     */
    public function isOperable()
    {
        if ($this->running_task || !$this->isStateGood()) {
            return false;
        }

        return true;
    }

    public function canEnableVNC()
    {
        return $this->isVNCSupported() && $this->isStateGood();
    }

    public function isStateGood()
    {
        return in_array($this->state, $this->goodStates(), true);
    }

    /**
     * Checks whether server supports VNC.
     *
     * @return bool
     */
    public function isVNCSupported()
    {
        return in_array($this->type, static::SVDS_TYPES);
    }

    /**
     * Checks whether server supports root password change.
     *
     * @return bool
     */
    public function isPwChangeSupported()
    {
        return $this->type === 'ovds';
    }

    /**
     * Checks whether server supports LiveCD.
     *
     * @return bool
     */
    public function isLiveCDSupported()
    {
        return in_array($this->type, static::SVDS_TYPES);
    }

    /**
     * Check whether server is virtual.
     *
     * @return bool
     */
    public function isVirtualDevice()
    {
        return in_array($this->type, static::VIRTUAL_DEVICES, true);
    }

    /**
     * Check whether server is dedicated.
     *
     * @return bool
     */
    public function isDedicatedDevice()
    {
        return $this->type === 'dedicated';
    }

    public function getIsBlocked()
    {
        return $this->state === static::STATE_BLOCKED;
    }

    /**
     * Checks whether server can be operated not.
     *
     * @throws NotSupportedException
     * @return bool
     * @see isOperable()
     */
    public function checkOperable()
    {
        if (!$this->isOperable()) {
            throw new NotSupportedException(Yii::t('hipanel:server', 'Server already has a running task. Can not start new.'));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function scenarioActions()
    {
        return [
            'reinstall' => 'resetup',
            'reset-password' => 'regen-root-password',
        ];
    }

    /**
     * During 5 days after the last expiration client is able to refuse server with full refund.
     * Method checks, whether 5 days passed.
     * @return bool
     */
    public function canFullRefuse()
    {
        return (time() - Yii::$app->formatter->asTimestamp($this->last_expires)) / 3600 / 24 < 5;
    }

    public function groupUsesForCharts()
    {
        return ServerHelper::groupUsesForChart($this->uses);
    }

    public function getUses()
    {
        return $this->hasMany(ServerUse::class, ['object_id' => 'id']);
    }

    public function getIps()
    {
        return $this->hasMany(Ip::class, ['device_id' => 'id'])->joinWith('links');
    }

    public function getSwitches()
    {
        return $this->hasMany(Server::class, ['obj_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'remoteid'            => Yii::t('hipanel:server', 'Remote ID'),
            'name'                => Yii::t('hipanel:server', 'Name'),
            'dc'                  => Yii::t('hipanel:server', 'DC'),
            'net'                 => Yii::t('hipanel:server', 'Switch'),
            'kvm'                 => Yii::t('hipanel:server', 'KVM'),
            'pdu'                 => Yii::t('hipanel:server', 'APC'),
            'rack'                => Yii::t('hipanel:server', 'Rack'),
            'ipmi'                => Yii::t('hipanel:server', 'IPMI'),
            'status_time'         => Yii::t('hipanel:server', 'Status update time'),
            'block_reason_label'  => Yii::t('hipanel:server', 'Block reason label'),
            'request_state_label' => Yii::t('hipanel:server', 'Request state label'),
            'mac'                 => Yii::t('hipanel:server', 'MAC'),
            'ips'                 => Yii::t('hipanel:server', 'IPs'),
            'label'               => Yii::t('hipanel:server', 'Internal note'),
            'os'                  => Yii::t('hipanel:server', 'OS'),
            'comment'             => Yii::t('hipanel:server', 'Comment'),
            'hwsummary'           => Yii::t('hipanel:server', 'HW summary'),
            'sale_time'           => Yii::t('hipanel:server', 'Sale time'),
            'expires'             => Yii::t('hipanel:server', 'Expires'),
        ]);
    }
}
