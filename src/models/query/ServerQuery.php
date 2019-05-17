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

class ServerQuery extends ActiveQuery
{
    public function withSales(): self
    {
        $this->joinWith('sales');
        $this->andWhere(['with_sales' => true]);

        return $this;
    }

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

    public function withMailSettings(): self
    {
        if (Yii::$app->user->can('part.read')) {
            $this->joinWith(['mailSettings']);
            $this->andWhere(['with_mailSettings' => 1]);
        }

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

    public function withMonitoringSettings(): self
    {
        if (Yii::$app->user->can('server.manage-settings')) {
            $this->joinWith(['monitoringSettings']);
            $this->andWhere(['with_monitoringSettings' => 1]);
        }

        return $this;
    }

    public function withConsumptions(): self
    {
        $this->joinWith('consumptions');
        $this->andWhere(['with_consumptions' => true]);

        return $this;
    }

    public function withHardwareSales(): self
    {
        $this->joinWith('hardwareSales');
        $this->andWhere(['with_hardwareSales' => true]);

        return $this;
    }
}
