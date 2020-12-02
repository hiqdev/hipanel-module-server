<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\stock\helpers\PartSort;
use hipanel\modules\stock\Module;
use hiqdev\hiart\ActiveRecord;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Configuration extends Widget
{
    public ActiveRecord $model;

    public array $configAttrs = [];

    private bool $loadAjax = true;

    private Module $stock;

    public function __construct(Module $stock, $config = [])
    {
        parent::__construct($config);
        $this->stock = $stock;
    }

    public function init()
    {
        parent::init();
        $this->loadAjax = !$this->model->isRelationPopulated('parts');
    }

    public function run()
    {
        if ($this->loadAjax) {
            $this->registerClientScript();
        }

        return $this->render('Configuration', [
            'model' => $this->model,
            'configAttrs' => $this->configAttrs,
            'loadAjax' => $this->loadAjax,
        ]);
    }

    public function takeContent(): string
    {
        if ($this->loadAjax) {
            return Html::tag('div', '', ['class' => 'server-parts']);
        }
        $parts = PartSort::byGeneralRules()->values($this->model->parts);
        $data = ArrayHelper::index($parts, 'id', ['model_type_label', 'model_id']);
        [$controller,] = $this->stock->createController('part');

        return $controller->renderPartial('_objectParts', compact('data'));
    }

    private function registerClientScript(): void
    {
        $url = Url::to(['@part/render-object-parts', 'id' => $this->model->id]);
        $this->view->registerJs("$('.server-parts').load('$url', function () { $(this).closest('.box').find('.overlay').remove(); });");
    }
}
