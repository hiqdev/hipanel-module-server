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

class AssignSwitchesPage extends \yii\base\Widget
{
    /**
     * @var array
     */
    public $switchVariants = [];

    /**
     * @var
     */
    public $form;

    /**
     * @var
     */
    public $models;

    /**
     * @var array
     */
    public $variantMap = [
        'pdu2' => 'pdu',
        'ipmi' => 'net',
        'nic2' => 'net',
    ];

    public function run()
    {
        return $this->render('AssignSwitchesPage', [
            'switchVariants' => $this->switchVariants,
            'form' => $this->form,
            'models' => $this->models,
        ]);
    }

    public function getFormFields(): array
    {
        $fields = [];
        foreach ($this->switchVariants as $name) {
            $fields[] = $name . '_id';
            $fields[] = $name . '_port';
        }

        return array_merge(['id'], $fields);
    }
}
