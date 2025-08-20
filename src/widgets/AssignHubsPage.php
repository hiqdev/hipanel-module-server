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
use hipanel\modules\server\widgets\combo\HubCombo;
use yii\base\Widget;
use yii\widgets\ActiveForm;

/**
 *
 * @property-read array $formFields
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

    public function getFormFields(): array
    {
        $fields = [];
        foreach (reset($this->models)->getHubVariants() as $name) {
            $fields[] = $name . '_id';
            $fields[] = $name . '_port';
        }

        return array_merge(['id'], $fields);
    }

    public function hasPort(string $variant): bool
    {
        return !in_array($variant, ['location', 'region'], true);
    }

    public function splitIntoGroups(AssignHubsForm $model): array
    {
        $hubVariants = $model->getHubVariants();

        return array_chunk($hubVariants, 4);
    }

    public function prepareHubComboOptions(string $variant): array
    {
        $renameMap = [
            'ipmi' => 'net',
        ];

        return array_filter([
            'name' => $variant,
            'url' => $variant === HubCombo::JBOD ? '/server/server/index' : null,
            'type' => $variant === HubCombo::JBOD ? 'server/server' : null,
            'hubType' => $renameMap[$variant] ?? preg_replace('/[0-9]+/', '', $variant),
        ]);
    }
}
