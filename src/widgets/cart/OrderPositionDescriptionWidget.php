<?php

namespace hipanel\modules\server\widgets\cart;

use hipanel\modules\server\cart\ServerOrderProduct;
use yii\base\Widget;

/**
 * Class OrderPositionDescriptionWidget renders description for Server order position
 * in cart.
 * @package hipanel\modules\server\widgets\cart
 */
class OrderPositionDescriptionWidget extends Widget
{
    /**
     * @var ServerOrderProduct
     */
    public $position;

    /** @inheritdoc */
    public function run()
    {
        echo $this->render('_orderPositionDescription', ['position' => $this->position]);
    }
}
