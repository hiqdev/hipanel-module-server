<?php

namespace hipanel\modules\server\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class ServerSwitcher extends Widget
{
    public $model;

    public function run()
    {
        if (!Yii::$app->user->can('support')) {
            return null;
        }

        $this->initClientScript();

        return $this->render('ServerSwitcher', ['model' => $this->model]);
    }

    protected function initClientScript()
    {
        $url = Url::to(['@server/view', 'id' => '']);
        $this->view->registerJs("
            $('.server-switcher select').on('select2:select', function (e) {
                var selectedId = this.value;
                window.location.href = '{$url}' + selectedId;
            });
        ");
    }
}
