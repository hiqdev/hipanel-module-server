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
use yii\base\Widget;
use yii\widgets\ActiveForm;

/**
 *
 * @property-read array $allPossibleFormFieldNames
 */
class AssignHubsPage extends Widget
{
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
            new AssignHubsGroup($nets, 'nets', Yii::t('hipanel:server', 'Switches')),
            new AssignHubsGroup($pdus, 'pdus', Yii::t('hipanel:server', 'APCs')),
            new AssignHubsGroup($others),
        ];
    }

    public function prepareHubComboOptions(string $variant, string $mainObjectType): array
    {
        // The "nic2" daisy-chain preview hint is only meaningful for Switches (net*) fields:
        // it previews a device that will be wired as a switch. It must never appear in the
        // APCs (pdu*) column, where the target device is bound as-is.
        $isNetField = $variant === 'net' || preg_match('/^net\d+$/', $variant) === 1;

        $hubTypes = match (true) {
            $isNetField => ['net', 'pdu'],
            $variant === 'ipmi' => ['net'],
            default => [preg_replace('/[\d+]+/', '', $variant)],
        };

        return array_filter([
            'name' => $variant,
            'url' => $variant === HubCombo::JBOD ? '/server/server/index' : null,
            'type' => $variant === HubCombo::JBOD ? 'server/server' : null,
            'hubTypes' => $hubTypes,
            'mainObjectType' => $isNetField ? $mainObjectType : null,
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
