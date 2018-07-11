<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

use hipanel\modules\finance\models\RUse;

class ServerUse extends RUse
{
    use \hipanel\base\ModelTrait;

    public function formName()
    {
        return 'ServerUse';
    }

    public static function tableName()
    {
        return 'server';
    }

    private function getTrafficTypes()
    {
        return ['server_traf_in', 'server_traf_max', 'server_traf'];
    }

    private function getBandwidthTypes()
    {
        return ['server_traf95_in', 'server_traf95_max', 'server_traf95'];
    }

    public function getDisplayAmount()
    {
        if (in_array($this->type, $this->getBandwidthTypes(), true)) {
            return round($this->last / pow(10, 6), 2);
        } elseif (in_array($this->type, $this->getTrafficTypes(), true)) {
            return round($this->total / pow(10, 9), 2);
        }

        return $this->total;
    }
}
