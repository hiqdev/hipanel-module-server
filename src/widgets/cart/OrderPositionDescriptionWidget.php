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

    /** {@inheritdoc} */
    public function run()
    {
        return $this->render('_orderPositionDescription', ['position' => $this->position]);
    }
}
