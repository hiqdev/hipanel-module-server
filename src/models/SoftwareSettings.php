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

class SoftwareSettings extends \hipanel\base\Model
{
    const SCENARIO_DEFAULT = 'dumb';

    public static function tableName()
    {
        return 'server';
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function scenarioActions()
    {
        return [
            'default' => 'set-software-settings',
        ];
    }

    public function rules()
    {
        return [
            [['id', 'delivery_time'], 'integer'],
            [['virtual_switch', 'ignore_ip_mon'], 'boolean'],
            [['os', 'version', 'ip_mon_comment', 'bw_limit', 'bw_group', 'failure_contacts', 'info'], 'string'],
        ];
    }
}
