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
use yii\helpers\Html;

/**
 * Class DiscountFormatter
 * Renders a button with popover to display current and upcoming discount.
 */
class DiscountFormatter extends Widget
{
    /**
     * @var float|string Current discount
     */
    public $current;

    /**
     * @var float|string Next discount
     */
    public $next;

    public function init()
    {
        parent::init();
        $this->current = floatval($this->current);
        $this->next = floatval($this->next);
    }

    public function run()
    {
        if ($this->current > 0 || $this->next > 0) {
            $this->getView()->registerJs("$('.discount-popover').popover();", \yii\web\View::POS_READY, 'discount-popover');

            return Html::a(Yii::$app->formatter->asPercent($this->current / 100), '#', [
                'onClick' => 'return false',
                'title' => Yii::t('hipanel:server', 'Next discount'),
                'class' => 'btn btn-default btn-xs discount-popover',
                'data-trigger' => 'focus',
                'data-content' => Yii::$app->formatter->asPercent($this->next / 100),
            ]);
        }

        return '';
    }
}
