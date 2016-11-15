<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use Yii;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;

class VNCFormatter extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $this->getView()->registerJs("$('.discount-popover').popover();", View::POS_READY, 'discount-popover');

        return Html::tag($this->tagName,
            Yii::$app->formatter->asPercent($this->current / 100),
            [
                'title'        => Yii::t('hipanel:server', 'Next discount'),
                'class'        => 'btn btn-default btn-xs discount-popover',
                'data-trigger' => 'focus',
                'data-content' => Yii::$app->formatter->asPercent($this->next / 100),
            ]);
    }
}
