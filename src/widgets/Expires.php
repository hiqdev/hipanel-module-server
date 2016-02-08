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

    public function init()
    {
        $expires = $this->model->expires;

        if (strtotime($expires) < strtotime("+30 days", time())) {
            $class = 'info';
        } elseif (strtotime($expires) < strtotime("+5 days", time())) {
            $class = 'warning';
        } else {
            $class = 'danger';
        }

        $this->color = $class;
        $this->label = Yii::$app->formatter->asDate($expires);
        parent::init();
    }

}
