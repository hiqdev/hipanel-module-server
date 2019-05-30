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
use Yii;

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
            [['name', 'client', 'state', 'state_label', 'type', 'type_label'], 'string'],
            [
                [
                    'data',
                    'name',
                    'subname',
                    'location',
                    'cpu',
                    'ram',
                    'hdd',
                    'traffic',
                    'lan',
                    'raid',
                    'enabled',
                    'sort_order',
                    'price',
                    'last_price',
                    'description',
                ], 'string'],
            [
                [
                    'name',
                    'subname',
                    'location',
                    'cpu',
                    'ram',
                    'hdd',
                    'traffic',
                    'lan',
                    'raid',
                    'enabled',
                    'sort_order',
                    'price',
                    'last_price',
                    'description',
                ], 'required', 'on' => ['create', 'update'],
            ],

        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'cpu'        => 'CPU',
            'ram'        => 'RAM',
            'hdd'        => 'HDD',
            'lan'        => 'LAN',
            'raid'       => 'RAID',
            'sort_order' => 'Sort order',
            'last_price' => 'Last price',
        ]);
    }
}
