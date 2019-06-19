<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models\query;

use hiqdev\hiart\ActiveQuery;
use Yii;

class ConfigQuery extends ActiveQuery
{
    public function withPrices(): self
    {
        $this->joinWith('prices');
        $this->andWhere(['with_prices' => true]);

        return $this;
    }

    public function getAvailable(): self
    {
        $this->addAction('get-available');

        return $this;
    }

    public function withSellerOptions(): self
    {
        $options = Yii::$app->user->isGuest ? ['seller' => Yii::$app->params['user.seller']] : [
            'seller' => Yii::$app->user->identity->seller,
            'seller_id' => Yii::$app->user->identity->seller_id,
        ];
        $this->andWhere($options);

        return $this;
    }
}
