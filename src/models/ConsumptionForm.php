<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\server\models;

use Yii;
use yii\base\Model;

class ConsumptionForm extends Model
{
    public $id;
    public $type;
    public $from;
    public $till;
    public $detalisation;
}
