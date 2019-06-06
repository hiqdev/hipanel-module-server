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

class ConfigQuery extends ActiveQuery
{
    public function withPlans(): self
    {
        $this->joinWith('plans');
        $this->andWhere(['with_plans' => true]);

        return $this;
    }
}
