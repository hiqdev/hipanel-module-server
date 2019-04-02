<?php

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
