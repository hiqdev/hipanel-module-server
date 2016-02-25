<?php
/**
 * @link    http://hiqdev.com/hipanel-module-domain
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/**
 * Class ChartOptions
 * @package hipanel\modules\server\widgets
 */
class ChartOptions extends \hipanel\widgets\ChartOptions
{
    /**
     * @inheritdoc
     */
    protected function initDefaults()
    {
        $id = $this->getId();

        parent::initDefaults();

        $this->ajaxOptions = ArrayHelper::merge([
            'beforeSend' => new JsExpression("function () {
                $('.{$id}').closest('.box').append($('<div>').addClass('overlay').html($('<i>').addClass('fa fa-refresh fa-spin')));
            }"),
            'complete' => new JsExpression("function () {
                $('.{$id}').closest('.box').find('.overlay').remove();
            }"),
        ], $this->ajaxOptions);
    }
}
