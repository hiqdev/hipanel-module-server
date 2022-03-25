<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

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
        $this->visible = Yii::$app->user->can('hub.read');
        $this->label = $this->getLabel();
    }

    public function getDataCellValue($model, $key, $index)
    {
        if (StringHelper::startsWith($this->attribute, 'ipmi', false) && ($ipmi = $model->getBinding($this->attribute)) !== null) {
            $device_ip = Html::encode($ipmi->device_ip);
            $link = Html::a($device_ip, "http://$device_ip/", ['target' => '_blank']) . ' ';

            return $link . $this->renderSwitchPort($ipmi);
        }

        return isset($model->bindings[$this->attribute]) ? $this->renderSwitchPort($model->bindings[$this->attribute]) : '';
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

        $label = Html::encode($binding->switch_label);
        $inn = $binding->switch_inn;
        $inn = Html::encode($inn ? "($inn) " : '');
        $main = Html::encode($binding->switch . ($binding->port ? ':' . $binding->port : ''));

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

        return (string) $replaceName;
    }
}
