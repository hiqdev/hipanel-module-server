<?php

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\models\Server;
use yii\base\Widget;

class ResourceConsumptionTable extends Widget
{
    /**
     * @var Server
     */
    public $model;

    public function run(): string
    {
        return $this->render('ResourceConsumptionTable', [
            'model' => $this->model,
        ]);
    }
}

