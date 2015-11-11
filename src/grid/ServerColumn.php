<?php

namespace hipanel\modules\server\grid;

use hipanel\grid\DataColumn;
use hipanel\modules\server\widgets\combo\ServerCombo;
use hiqdev\combo\Combo;
use yii\helpers\Html;

class ServerColumn extends DataColumn
{
    public $attribute = 'server_id';
    public $nameAttribute = 'server';
    public $format = 'html';

    public function init () {
        parent::init();
        if (!$this->filterInputOptions['id']) {
            $this->filterInputOptions['id'] = $this->attribute;
        }
        if (!$this->filter && $this->grid->filterModel) {
            $this->filter = ServerCombo::widget([
                'attribute'           => $this->attribute,
                'model'               => $this->grid->filterModel,
                'formElementSelector' => 'td',
            ]);
        };
    }

    public function getDataCellValue ($model, $key, $index) {
        return Html::a($model->{$this->nameAttribute}, ['/server/server/view', 'id' => $model->{$this->attribute}]);
    }
}
