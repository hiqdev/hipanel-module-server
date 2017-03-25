<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

/**
 * @see    http://hiqdev.com/hipanel-module-domain
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

        if (strtotime($expires) < time()) {
            $class = 'danger';
        } elseif (strtotime($expires) < strtotime('+5 days', time())) {
            $class = 'warning';
        } elseif (strtotime($expires) < strtotime('+30 days', time())) {
            $class = 'info';
        } else {
            $class = 'default';
        }

        $this->color = $class;
        $this->label = Yii::$app->formatter->asDate($expires);
        parent::init();
    }
}
