<?php

namespace hipanel\modules\server\models;

class MonitoringSettings extends \hipanel\base\Model
{
    public function rules()
    {
        return [
            [['id', 'server', 'emails', 'minutes', 'nic_media', 'channel_load', 'watch_trafdown', 'vcdn_only', 'comment'], 'safe'],
        ];
    }
}
