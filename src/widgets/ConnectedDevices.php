<?php

declare(strict_types=1);

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\models\Binding;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\widgets\combo\HubCombo;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class ConnectedDevices extends Widget
{
    private const string TITLE = 'Connected to this device';
    private const string NIC_PORTS = 'NIC ports';
    private const string POWER_SOCKETS = 'Power sockets';
    private const string SOCKET = 'Socket {port}';
    private const string PORT = 'Port {port}';
    private const string NIC_PORT = 'NIC {no}, port {port}';
    private const string DEVICE_NIC = '{device} (NIC {no})';
    private const string DEVICE_POWER = '{device} (Power {no})';
    private const string NIC_NO = '/nic(\d+)$/';

    public Hub|Server $model;

    public function run(): string
    {
        if (!Yii::$app->user->can('hub.read')) {
            return '';
        }
        $bindings = $this->findBindings();
        if ($bindings === []) {
            return '';
        }
        $serverIds = $this->findServerIds($bindings);
        $groups = [self::NIC_PORTS => [], self::POWER_SOCKETS => []];
        foreach ($bindings as $binding) {
            $isSocket = $this->isSocket($binding);
            $groups[$isSocket ? self::POWER_SOCKETS : self::NIC_PORTS][] = [
                'port' => $this->describePort($binding, $isSocket),
                'device' => $this->linkDevice($binding, $isSocket, !isset($serverIds[$binding->base_device_id])),
            ];
        }

        return $this->render('ConnectedDevices', [
            'title' => Yii::t('hipanel:server', self::TITLE),
            'groups' => array_filter(array_combine(
                array_map(static fn(string $label): string => Yii::t('hipanel:server', $label), array_keys($groups)),
                array_map($this->sortByPort(...), $groups),
            )),
        ]);
    }

    /**
     * @return Binding[]
     */
    private function findBindings(): array
    {
        $own = Binding::find()->andWhere(['switch_id' => $this->model->id])->limit(-1)->all();
        $nics = Binding::find()->andWhere(['switch_name_ilike' => $this->model->name . 'nic'])->limit(-1)->all();
        $nics = array_filter($nics, fn(Binding $b): bool => preg_match('/^' . preg_quote((string)$this->model->name, '/') . 'nic\d+$/', (string)$b->switch_name) === 1);

        return [...$own, ...$nics];
    }

    /**
     * @param Binding[] $bindings
     * @return array<int, true>
     */
    private function findServerIds(array $bindings): array
    {
        $ids = array_values(array_unique(array_map(static fn(Binding $b): int => (int)$b->base_device_id, $bindings)));
        $servers = Server::find()->andWhere(['ids' => $ids])->limit(-1)->all();

        return array_fill_keys(array_map(static fn(Server $server): int => (int)$server->id, $servers), true);
    }

    private function isSocket(Binding $binding): bool
    {
        return (int)$binding->switch_id === (int)$this->model->id && $this->model->type === HubCombo::PDU;
    }

    private function describePort(Binding $binding, bool $isSocket): string
    {
        $port = $binding->port === null || $binding->port === '' ? '—' : $binding->port;
        if ($isSocket) {
            return Yii::t('hipanel:server', self::SOCKET, ['port' => $port]);
        }
        if (preg_match(self::NIC_NO, (string)$binding->switch_name, $nic) === 1) {
            return Yii::t('hipanel:server', self::NIC_PORT, ['no' => $nic[1], 'port' => $port]);
        }

        return Yii::t('hipanel:server', self::PORT, ['port' => $port]);
    }

    private function linkDevice(Binding $binding, bool $isSocket, bool $isHub): string
    {
        $no = preg_match(self::NIC_NO, (string)$binding->device_name, $nic) === 1 ? $nic[1] : 1;
        $text = Yii::t('hipanel:server', $isSocket ? self::DEVICE_POWER : self::DEVICE_NIC, [
            'device' => $binding->base_device_name,
            'no' => $no,
        ]);
        $route = $isHub ? '@hub/assign-hubs' : '@server/assign-hubs';

        return Html::a(Html::encode($text), [$route, 'id' => $binding->base_device_id]);
    }

    private function sortByPort(array $rows): array
    {
        usort($rows, static fn(array $a, array $b): int => strnatcasecmp($a['port'], $b['port']));

        return $rows;
    }
}
