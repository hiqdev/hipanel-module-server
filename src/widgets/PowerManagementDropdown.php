<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\models\Server;
use hipanel\widgets\AjaxModalWithTemplatedButton;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\web\User;

class PowerManagementDropdown extends Widget
{
    public Server $model;

    private User $user;

    public function init()
    {
        parent::init();
        $this->user = Yii::$app->user;
    }

    public function run()
    {
        if (!$this->model->canControlPower()) {
            return '';
        }

        return Html::tag('div', $this->getButton() . Dropdown::widget([
                'encodeLabels' => false,
                'options' => ['class' => 'pull-right'],
                'items' => $this->getItems(),
            ]), ['class' => 'dropdown', 'style' => 'display: inline-block;']);
    }

    private function getButton(): string
    {
        return Html::button(
            Yii::t('hipanel:server', 'Power management') . Html::tag('span', null, ['class' => 'caret', 'style' => 'margin-left: .5rem']),
            [
                'class' => 'btn btn-sm btn-default dropdown-toggle',
                'data' => [
                    'toggle' => 'dropdown',
                ],
            ]
        );
    }

    private function getItems(): array
    {
        $result = [];
        $items = [
            'bulk-power-on' => Yii::t('hipanel:server', 'Power ON'),
            'bulk-power-off' => Yii::t('hipanel:server', 'Power OFF'),
            'bulk-reboot' => Yii::t('hipanel:server', 'Reboot'),
            'bulk-boot-to-bios' => Yii::t('hipanel:server', 'Boot to BIOS'),
            'bulk-boot-via-network' => Yii::t('hipanel:server', 'Boot via network'),
        ];
        foreach ($items as $scenario => $label) {
            $result[] = AjaxModalWithTemplatedButton::widget([
                'ajaxModalOptions' => [
                    'bulkPage' => true,
                    'id' => $scenario . '-modal',
                    'scenario' => $scenario,
                    'header' => Html::tag('h4', $label, ['class' => 'modal-title']),
                    'toggleButton' => [
                        'tag' => 'a',
                        'label' => $label,
                    ],
                    'handleSubmit' => false,
                ],
                'toggleButtonTemplate' => '<li>{toggleButton}</li>',
            ]);
        }

        return $result;
    }
}