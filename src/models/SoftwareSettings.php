<?php

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
            'default' => 'set-software-settings'
        ];
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['virtual_switch', 'ignore_ip_mon'], 'boolean'],
            [['os', 'version', 'ip_mon_comment', 'bw_limit', 'bw_group', 'failure_contacts', 'info'], 'string'],
        ];
    }
}
