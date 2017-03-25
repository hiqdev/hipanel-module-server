<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\models\Server;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\Html;

class StateFormatter extends Widget
{
    /**
     * @var Server
     */
    public $model;

    public function init()
    {
        parent::init();
        if (!($this->model instanceof Server)) {
            throw new InvalidParamException('Model should be an instance of Server model');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        if ($this->model->state !== 'blocked') {
            $value = Yii::$app->formatter->asDate($this->model->expires);
        } else {
            $value = Yii::t('hipanel:server', 'Blocked {reason}', ['reason' => Yii::t('hipanel:block-reasons', $this->model->block_reason_label)]);
        }

        $class = ['label'];

        if (strtotime('+7 days', time()) < strtotime($this->model->expires)) {
            $class[] = 'label-info';
        } elseif (strtotime('+3 days', time()) < strtotime($this->model->expires)) {
            $class[] = 'label-warning';
        } else {
            $class[] = 'label-danger';
        }
        $html = Html::tag('span', $value, ['class' => implode(' ', $class)]);

        return $html;
    }
}
