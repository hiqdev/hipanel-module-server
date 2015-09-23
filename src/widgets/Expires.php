<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\models\Server;
use Yii;

class Expires extends \hipanel\widgets\Label
{
    /**
     * @var Server
     */
    public $model;

    public function run () {
        $expires = $this->model->expires;
        if (strtotime("+30 days", time()) < strtotime($expires)) $class = 'info';
        elseif (strtotime("+0 days", time()) < strtotime($expires)) $class = 'warning';
        else $class = 'danger';

        $this->color = $class;
        $this->label = Yii::$app->formatter->asDate($expires);
        parent::run();
    }

}
