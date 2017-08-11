<?php

namespace hipanel\modules\server\models;

class MonitoringSettings extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['id', 'nic_media', 'channel_load'], 'integer'],
            [['watch_trafdown', 'vcdn_only'], 'boolean'],
            [['server', 'emails', 'minutes', 'comment'], 'string'],
        ];
    }

    public function getEmailsDefault()
    {
        return 'notify@advancedhosters.com';
    }

    public function getNicMediaDefault()
    {
        return 40696899;
    }

    public function getChanelLoadDefault()
    {
        return 90;
    }
}
