<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

class MonitoringSettings extends \hipanel\base\Model
{
    const SCENARIO_DEFAULT = 'dumb';

    public static function tableName()
    {
        return 'server';
    }

    public function rules()
    {
        return [
            [['id', 'nic_media', 'channel_load'], 'integer'],
            [['watch_trafdown', 'vcdn_only'], 'boolean'],
            [['server', 'emails', 'minutes', 'comment'], 'string'],
        ];
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function scenarioActions()
    {
        return [
            'default' => 'set-monitoring-settings',
        ];
    }
}
