<?php

declare(strict_types=1);


namespace hipanel\modules\server\widgets;

use hipanel\modules\server\assets\ApplyToAllAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\Json;

class ApplyToAllWidget extends Widget
{
    public string $formId;
    public string $modelFormName;
    public array $attributes = [];

    public function run()
    {
        ApplyToAllAsset::register($this->view);

        $formName = mb_strtolower($this->modelFormName);
        $options = Json::htmlEncode([
            'formId' => $this->formId,
            'formName' => $formName,
            'attributes' => $this->attributes,
            'linkText' => Yii::t('hipanel', 'Apply to all'),
        ]);
        $this->view->registerJs("\$('#$this->formId').applyToAll($options);");

        return parent::run();
    }
}
