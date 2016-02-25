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

class ServerUseSearch extends ServerUse
{
    use \hipanel\base\SearchModelTrait;

    public function formName()
    {
        return 'ServerUse';
    }

    public static function index()
    {
        return 'Servers';
    }

    public static function type()
    {
        return 'Server';
    }
}
