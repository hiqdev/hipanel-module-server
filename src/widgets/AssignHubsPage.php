<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\helpers\AssignHubsGroup;
use hipanel\modules\server\widgets\combo\HubCombo;
use Yii;
use yii\helpers\Url;
use yii\base\Widget;
use yii\widgets\ActiveForm;

/**
 *
 * @property-read array $allPossibleFormFieldNames
 */
class AssignHubsPage extends Widget
{
    private const string NETWORK_UPLINKS = "Network uplinks: where this device's ports plug in";
    private const string POWER_INLETS = 'Power inlets: which socket feeds this device';
    private const string HUB_PORT = 'Port on the hub';
    private const string SOCKET_NO = 'Socket no.';
    private const string PORT = 'Port';
    private const string DEVICE_PORTS = 'Device ports';
    private const string NO_PORTS = 'No ports are recorded on this hub yet';

    /** @var AssignHubsForm[] */
    public array $models;
    public ActiveForm $form;

    public function run()
    {
        return $this->render('AssignHubsPage', [
            'form' => $this->form,
            'models' => $this->models,
        ]);
    }

    public function getAllPossibleFormFieldNames(): array
    {
        $fields = [];
        foreach ($this->models as $model) {
            foreach ($model->getHubVariants() as $name) {
                $fields[] = $name . '_id';
                $fields[] = $name . '_port';
            }
        }

        return ['id', ...array_unique($fields)];
    }

    public function hasPort(string $variant): bool
    {
        return !in_array($variant, ['location', 'region'], true);
    }

    public function splitIntoGroups(array $hubVariants): array
    {
        $nets = [];
        $pdus = [];
        $others = [];

        foreach ($hubVariants as $v) {
            // put plain 'net' into nets group, plain 'pdu' into pdus group
            if ($v === 'net' || preg_match('/^net\d+$/', $v)) {
                $nets[] = $v;
                continue;
            }
            if ($v === 'pdu' || preg_match('/^pdu\d+$/', $v)) {
                $pdus[] = $v;
                continue;
            }
            $others[] = $v;
        }

        // Sort net/pdu groups by their numeric suffix (plain 'net'/'pdu' go first), others alphabetically
        $sortBySuffix = static function (string $a, string $b): int {
            // plain names without digits should come first
            preg_match('/\d+$/', $a, $ma);
            preg_match('/\d+$/', $b, $mb);
            $na = isset($ma[0]) ? (int)$ma[0] : 0;
            $nb = isset($mb[0]) ? (int)$mb[0] : 0;

            // ensure plain 'net'/'pdu' (na=0) come before numbered ones
            return $na <=> $nb;
        };

        usort($nets, $sortBySuffix);
        usort($pdus, $sortBySuffix);
        sort($others, SORT_NATURAL | SORT_FLAG_CASE);

        return [
            new AssignHubsGroup($nets, 'nets', Yii::t('hipanel:server', self::NETWORK_UPLINKS)),
            new AssignHubsGroup($pdus, 'pdus', Yii::t('hipanel:server', self::POWER_INLETS)),
            new AssignHubsGroup($others),
        ];
    }

    public function portPlaceholder(string $variant): string
    {
        $placeholder = match (preg_replace('/\d+$/', '', $variant)) {
            'net' => self::HUB_PORT,
            'pdu' => self::SOCKET_NO,
            default => self::PORT,
        };

        return Yii::t('hipanel:server', $placeholder);
    }

    public function devicePortsData(): array
    {
        return [
            'ports-url' => Url::to(['@hub/ports']),
            'ports-title' => Yii::t('hipanel:server', self::DEVICE_PORTS),
            'ports-empty' => Yii::t('hipanel:server', self::NO_PORTS),
        ];
    }

    public function prepareHubComboOptions(string $variant): array
    {
        $hubTypes = match (true) {
            $variant === 'ipmi' => ['net'],
            default => [preg_replace('/[\d+]+/', '', $variant)],
        };

        return array_filter([
            'name' => $variant,
            'url' => $variant === HubCombo::JBOD ? '/server/server/index' : null,
            'type' => $variant === HubCombo::JBOD ? 'server/server' : null,
            'hubTypes' => $hubTypes,
        ]);
    }

    public function getAttributeLabel(AssignHubsForm $model, string $variant): string|false
    {
        if (str_starts_with($variant, 'net') || str_starts_with($variant, 'pdu') || str_ends_with($variant, '_port')) {
            return false;
        }

        return $model->getAttributeLabel($variant);
    }
}
