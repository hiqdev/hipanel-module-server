<?php

namespace hipanel\modules\server\models;

class SoftwareSettings extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['virtual_switch', 'ignore_ip_mon'], 'boolean'],
            [['os', 'version', 'ip_mon_comment', 'bw_limit', 'bw_group', 'failure_contacts', 'info'], 'string'],
        ];
    }

    public function getOsDefault()
    {
        return 'FreeBSD';
    }

    public function getBwLimitDefault()
    {
        return '95%';
    }
}
