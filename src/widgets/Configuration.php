<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\stock\helpers\PartSort;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\Module;
use hiqdev\hiart\ActiveRecord;
use Yii;
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
        [$controller,] = $this->stock->createController('part');

        return $controller->renderPartial('_objectParts', ['parts' => $parts]);
    }

    public function getTotalsData(): array
    {
        $result = [];
        foreach (ArrayHelper::index($this->model->parts, 'id', 'company') as $company => $parts) {
            $sums = [];
            $byCurrency = ArrayHelper::index(array_filter($parts, static fn(Part $part): bool => $part->currency !== null), 'id', 'currency');
            foreach ($byCurrency as $currency => $rows) {
                $sums[$currency] = 0;
                foreach (ArrayHelper::getColumn($rows, 'price') as $price) {
                    if (empty($price)) {
                        continue;
                    }
                    $sums[$currency] = bcadd($sums[$currency], $price, 2);
                }
            }
            array_walk($sums, static function (&$sum, $currency): void {
                $sum = Yii::$app->formatter->asCurrency($sum, $currency);
            });
            $result[] = [
                'company' => $company,
                'count' => Yii::t('hipanel:server', '{0} pcs', [count($parts)]),
                'sum' => implode(', ', $sums),
            ];
        }

        return $result;
    }

    private function registerClientScript(): void
    {
        $url = Url::to(['@part/render-object-parts', 'id' => $this->model->id]);
        $this->view->registerJs("$('.server-parts').load('$url', function () { $(this).closest('.box').find('.overlay').remove(); });");
    }
}
