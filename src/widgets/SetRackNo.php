<?php

namespace hipanel\modules\server\widgets;


use hipanel\modules\server\models\AssignSwitchInterface;
use yii\base\Widget;
use yii\widgets\ActiveForm;

class SetRackNo extends Widget
{
    /**
     * @var AssignSwitchInterface[]
     */
    public array $models;

    public AssignSwitchInterface $model;

    public ActiveForm $form;

    public function run(): string
    {
        return $this->render('SetRackNo', ['model' => $this->model, 'models' => $this->models, 'form' => $this->form]);
    }
}
