<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets\cart;

use hipanel\modules\server\cart\ServerOrderDedicatedProduct;
use hipanel\modules\server\cart\ServerOrderProduct;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class OrderPositionDescriptionWidget renders description for Server order position
 * in cart.
 */
class OrderPositionDescriptionWidget extends Widget
{
    /**
     * @var ServerOrderProduct|ServerOrderDedicatedProduct
     */
    public $position;

    public function init()
    {
        $this->view->registerCss(<<<CSS
            .cart-row .dl-config { display: none; }
            
            dl.dl-config {
                padding: 1em 0 0;
                display: flex;
                flex-flow: row wrap;
            }

            dl.dl-config dt {
                flex-basis: 20%;
                text-align: left;
                width: auto;
            }

            dl.dl-config dd {
                flex-basis: 70%;
                flex-grow: 1;
                margin: 0;
                padding: 0;
            }
            
            .dl-config time {
                font-weight: 600;
                padding: 0 5px;
                display: inline-block;
                background: linear-gradient(to bottom, rgba(216, 27, 96, 1) 0%, rgba(216, 27, 96, 1) 100%);
                text-shadow: 0 0 2px #fff;
                color: white;
                border-radius: 0px;
            }
CSS
        , [], __CLASS__ . '_client_css');
    }

    /** {@inheritdoc} */
    public function run()
    {
        return $this->render('_orderPositionDescription', ['position' => $this->position]);
    }

    /**
     * @param array $items
     * @return string
     */
    public function formatConfig(array $items = []): string
    {
        $html = '';
        foreach ($items as $label => $value) {
            if (empty($value)) {
                continue;
            }
            $html .= Html::tag('dt', $label) . Html::tag('dd', $value);
        }

        return Html::tag('dl', $html, ['class' => 'dl-horizontal dl-config']);
    }
}
