<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class ServerSwitcher extends Widget
{
    public $model;

    public function run()
    {
        if (!Yii::$app->user->can('server.read')) {
            return "";
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
