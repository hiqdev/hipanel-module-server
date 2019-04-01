<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models\query;

use hiqdev\hiart\ActiveQuery;
use Yii;

class HubQuery extends ActiveQuery
{
    public function withBindings(): self
    {
        if (Yii::$app->user->can('hub.read')) {
            $this->joinWith('bindings');
            $this->andWhere(['with_bindings' => true]);
        }

        return $this;
    }
}
