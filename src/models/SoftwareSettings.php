<?php

namespace hipanel\modules\server\models;

class SoftwareSettings extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['os', 'version', 'virtual_switch', 'ignore_ip_mon', 'ip_mon_comment', 'bw_limit', 'bw_group', 'failure_contacts', 'info'], 'safe'],
        ];
    }
}
