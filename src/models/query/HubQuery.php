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

use hipanel\modules\server\models\HubSearch;
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

    public function withResources(): self
    {
        $this->joinWith('resources');
        $this->andWhere(['with_resources' => true]);

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

    public function withDeleted(): self
    {
        $this->andWhere(['state_in' => [
            HubSearch::STATE_OK,
            HubSearch::STATE_DELETED,
            HubSearch::STATE_DISABLED,
        ]]);

        return $this;
    }
}
