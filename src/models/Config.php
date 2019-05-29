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

class Config extends Model
{
    use ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'client_id', 'type_id', 'state_id'], 'integer'],
            [['client', 'client_label', 'state', 'state_label', 'type', 'type_label', 'name'], 'string'],
            ['id', 'required', 'on' => 'delete'],
        ]);
    }
}
