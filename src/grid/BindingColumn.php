<?php

namespace hipanel\modules\server\grid;

use hipanel\helpers\StringHelper;
use hipanel\modules\server\models\Binding;
use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;

class BindingColumn extends DataColumn
{
    public $format = 'raw';

    public $filter = false;

    public function init()
    {
        parent::init();
        $this->visible = Yii::$app->user->can('admin');
        $this->label = $this->getLabel();
    }

    public function getDataCellValue($model, $key, $index)
    {
        if (StringHelper::startsWith($this->attribute, 'ipmi', false) && ($ipmi = $model->getBinding($this->attribute)) !== null) {
            $link = Html::a($ipmi->device_ip, "http://$ipmi->device_ip/", ['target' => '_blank']) . ' ';

            return $link . $this->renderSwitchPort($ipmi);
        }

        return $this->renderSwitchPort($model->bindings[$this->attribute]);
    }

    /**
     * @param Binding|null $binding
     * @return string
     */
    protected function renderSwitchPort(?Binding $binding): string
    {
        if ($binding === null) {
            return '';
        }

        $label = $binding->switch_label;
        $inn = $binding->switch_inn;
        $inn = $inn ? "($inn) " : '';
        $main = $binding->switch . ($binding->port ? ':' . $binding->port : '');

        return "$inn<b>$main</b> $label";
    }

    /**
     * @return string
     */
    private function getLabel(): string
    {
        $defaultLabel = $this->getHeaderCellLabel();
        $addColon = preg_replace('/(\d+)/', ':${1}', $defaultLabel);
        $replaceName = preg_replace(['/Net/', '/Pdu/'], ['Switch', 'APC'], $addColon);

        return (string)$replaceName;
    }
}
