<?php
/**
 * @link    http://hiqdev.com/hipanel-module-server
 * @license http://hiqdev.com/hipanel-module-server/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\models;

use hipanel\modules\finance\models\RUse;
use Yii;
use yii\base\InvalidParamException;

class ServerUse extends RUse
{
    use \hipanel\base\ModelTrait;

    public function formName()
    {
        return 'ServerUse';
    }

    public static function index()
    {
        return 'servers';
    }

    public static function type()
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
        if (in_array($this->type, $this->getBandwidthTypes())) {
            return round($this->last / pow(10, 6), 2);
        } elseif (in_array($this->type, $this->getTrafficTypes())) {
            return round($this->total / pow(10, 9), 2);
        }

        return $this->total;
    }
}
