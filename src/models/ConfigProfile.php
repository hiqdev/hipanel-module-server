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

use hipanel\base\Model;
use hipanel\base\ModelTrait;

class ConfigProfile extends Model
{
    use ModelTrait;

    /** {@inheritdoc} */
    public static function tableName()
    {
        return 'profile';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'label', 'type', 'class'], 'string'],
            [['class'], 'default', 'value' => 'config'],
        ];
    }
}
