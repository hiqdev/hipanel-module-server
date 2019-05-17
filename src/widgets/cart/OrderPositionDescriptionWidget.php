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

use hipanel\modules\server\cart\ServerOrderProduct;
use yii\base\Widget;

/**
 * Class OrderPositionDescriptionWidget renders description for Server order position
 * in cart.
 */
class OrderPositionDescriptionWidget extends Widget
{
    /**
     * @var ServerOrderProduct
     */
    public $position;

    /** {@inheritdoc} */
    public function run()
    {
        echo $this->render('_orderPositionDescription', ['position' => $this->position]);
    }
}
