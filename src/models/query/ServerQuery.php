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

class ServerQuery extends ActiveQuery
{
    public function withBindings(): self
    {
        if (Yii::$app->user->can('hub.read')) {
            $this->joinWith('bindings');
            $this->andWhere(['with_bindings' => true]);
        }

        return $this;
    }

    public function withUses(): self
    {
        $this->joinWith('uses');
        $this->andWhere(['with_uses' => true]);

        return $this;
    }

    public function withBlocking(): self
    {
        $this->joinWith('blocking');
        $this->andWhere(['with_blocking' => true]);

        return $this;
    }

    public function withHardwareSettings(): self
    {
        if (Yii::$app->user->can('part.read')) {
            $this->joinWith(['hardwareSettings']);
            $this->andWhere(['with_hardwareSettings' => 1]);
        }

        return $this;
    }

    public function withSoftwareSettings(): self
    {
        if (Yii::$app->user->can('server.manage-settings')) {
            $this->joinWith(['softwareSettings']);
            $this->andWhere(['with_softwareSettings' => 1]);
        }

        return $this;
    }

    public function withConsumptions(): self
    {
        $this->joinWith('consumptions');
        $this->andWhere(['with_consumptions' => true]);

        return $this;
    }

    public function withHardwarePrices(): self
    {
        $this->andWhere(['with_hardwarePrices' => true]);

        return $this;
    }
}
