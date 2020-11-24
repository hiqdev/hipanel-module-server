<?php

namespace hipanel\modules\server\forms;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\modules\server\models\Server;
use Yii;

class PowerManagementForm extends Model
{
    use ModelTrait;

    private array $servers = [];

    public static function tableName()
    {
        return 'server';
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reason'], 'string', 'min' => 2],
            [['reason'], 'required', 'on' => [
                'bulk-power-on',
                'bulk-power-on',
                'bulk-power-off',
                'bulk-reboot',
                'bulk-boot-to-bios',
                'bulk-boot-via-network',
            ]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'reason' => Yii::t('hipanel:server', 'Reason'),
        ];
    }

    public function getServers(): array
    {
        return $this->servers;
    }

    public function scenarioActions(): array
    {
        return [
            'bulk-power-on' => 'power-on',
            'bulk-power-off' => 'power-off',
            'bulk-reboot' => 'reboot',
            'bulk-boot-to-bios' => 'boot-to-bios',
            'bulk-boot-via-network' => 'boot-via-network',
        ];
    }

    public function setServers(array $servers): void
    {
        $this->servers = $servers;
    }

    public function getIncluded(): array
    {
        return array_filter($this->getServers(), fn(Server $server) => $this->canPowerManage($server));
    }

    public function getNotIncluded(): array
    {
        return array_filter($this->getServers(), fn(Server $server) => !$this->canPowerManage($server));
    }

    public function canPowerManage(Server $server): bool
    {
        return $server->type === 'vds' && $server->canControlPower();
    }
}
