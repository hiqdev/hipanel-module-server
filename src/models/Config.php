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

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'client_id', 'type_id', 'state_id', 'us_tariff_id', 'nl_tariff_id'], 'integer'],
            [['name', 'client', 'state', 'state_label', 'type', 'type_label'], 'string'],
            [['sort_order'], 'integer', 'min'=>0],
            [
                [
                    'data',
                    'name',
                    'label',
                    'us_tariff',
                    'nl_tariff',
                    'cpu',
                    'ram',
                    'hdd',
                    'ssd',
                    'traffic',
                    'lan',
                    'raid',
                    'descr',
                ], 'string'],
            [
                ['client_id', 'name', 'label', 'cpu', 'ram'],
                'required', 'on' => ['create', 'update'],
            ],

            ['id', 'required', 'on' => ['delete', 'enable', 'disable']]
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'us_tariff_id' => 'USA tariff',
            'nl_tariff_id' => 'Netherlands tariff',
            'us_tariff'    => 'USA tariff',
            'nl_tariff'    => 'Netherlands tariff',
            'label'        => 'Subname',
            'cpu'          => 'CPU',
            'ram'          => 'RAM',
            'hdd'          => 'HDD',
            'ssd'          => 'SSD',
            'lan'          => 'LAN',
            'raid'         => 'RAID',
            'sort_order'   => 'Sort order',
        ]);
    }
}
