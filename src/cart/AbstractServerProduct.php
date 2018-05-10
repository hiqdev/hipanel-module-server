<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

use DateTime;
use hipanel\modules\finance\cart\AbstractCartPosition;
use hipanel\modules\server\models\Server;
use hiqdev\yii2\cart\DontIncrementQuantityWhenAlreadyInCart;
use Yii;

/**
 * Class AbstractServerProduct is an abstract cart position for server produce.
 */
abstract class AbstractServerProduct extends AbstractCartPosition implements DontIncrementQuantityWhenAlreadyInCart
{
    /**
     * @var Server
     */
    protected $_model;

    /** {@inheritdoc} */
    public function getIcon()
    {
        return '<i class="fa fa-server"></i>';
    }

    /** {@inheritdoc} */
    public function getQuantityOptions()
    {
        $result = [];
        foreach ([1, 3, 6, 12] as $n) {
            $date = (new DateTime())->add(new \DateInterval("P{$n}M"));

            $result[$n] = Yii::t('hipanel:server', '{n, plural, one{# month} other{# months}} till {date}', [
                'n' => $n,
                'date' => Yii::$app->formatter->asDate($date),
            ]);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function serializationMap()
    {
        $parent = parent::serializationMap();
        $parent['_model'] = $this->_model;
        return $parent;
    }
}
