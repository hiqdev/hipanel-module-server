<?php

declare(strict_types=1);

namespace hipanel\modules\server\widgets;

use hipanel\helpers\Url;
use hipanel\modules\server\forms\DeviceRangeForm;
use hipanel\modules\server\forms\ServerForm;
use hipanel\modules\server\models\Hub;
use Yii;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;

/**
 *
 * @property-read string $createLink
 */
class CreateDeviceRangeButton extends Widget
{
    public string $createLink;
    public ServerForm|Hub $deviceForm;
    public array $deviceAttributes = [];
    public ?array $typeOptions = null;

    public function init(): void
    {
        $this->view->on(View::EVENT_END_BODY, function () {
            $model = new DeviceRangeForm();
            Modal::begin([
                'id' => $this->getId(),
                'size' => Modal::SIZE_SMALL,
                'header' => Html::tag('h4', Yii::t('hipanel:server', 'Create devices by range'), ['class' => 'modal-title']),
                'toggleButton' => false,
            ]);

            $form = ActiveForm::begin([
                'action' => Url::to(['create-by-range']),
                'method' => 'POST',
            ]);

            echo $form->field($model, 'range');

            foreach ($this->deviceAttributes as $attribute) {
                if (str_starts_with($attribute, 'type')) {
                    echo $form->field($this->deviceForm, $attribute)->dropDownList($this->typeOptions ?? $this->deviceForm->typeOptions, ['prompt' => '--']);
                } else {
                    echo $form->field($this->deviceForm, $attribute);
                }
            }

            echo Html::submitButton(Yii::t('hipanel:server', 'Generate devices'), ['class' => 'btn btn-success btn-block']);

            ActiveForm::end();

            Modal::end();
        });
    }

    public function run(): string
    {
        return implode("", [
            Html::beginTag('div', ['class' => 'btn-group']),
            $this->createLink,
            Html::button(
                Html::tag('span', null, ['class' => 'caret']) .
                Html::tag('span', Yii::t('hipanel', 'Toggle dropdown'), ['class' => 'sr-only']),
                ['class' => 'btn btn-success btn-sm dropdown-toggle', 'data-toggle' => 'dropdown']
            ),
            Dropdown::widget([
                'items' => [
                    [
                        'label' => Yii::t('hipanel:server', 'Create by range'),
                        'url' => '#',
                        'linkOptions' => [
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '#' . $this->getId(),
                            ],
                        ],
                    ],
                ],
            ]),
            Html::endTag('div'),
        ]);
    }
}
