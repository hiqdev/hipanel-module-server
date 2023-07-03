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

use hipanel\modules\server\models\AssignSwitchInterface;
use yii\base\Widget;

class AssignSwitchesPage extends Widget
{
    /**
     * Rack -> location Location -> location
     * @var
     */
    public $form;

    /** @var AssignSwitchInterface[] */
    public array $models;

    public array $variantMap = [
        'pdu2' => 'pdu',
        'ipmi' => 'net',
        'net2' => 'net',
    ];

    public function run()
    {
        return $this->render('AssignSwitchesPage', [
            'form' => $this->form,
            'models' => $this->models,
        ]);
    }

    public function getFormFields(): array
    {
        $fields = [];
        foreach (reset($this->models)->switchVariants as $name) {
            $fields[] = $name . '_id';
            $fields[] = $name . '_port';
        }

        return array_merge(['id'], $fields);
    }

    public function hasPort(string $variant): bool
    {
        return $variant !== 'location';
    }
}
