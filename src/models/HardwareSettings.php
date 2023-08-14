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

use Yii;

/**
 * Class HardwareSettings
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @property float|int|string $units numeric units number (e.g. 1), or a fraction (3/5)
 */
class HardwareSettings extends \hipanel\base\Model
{
    const SCENARIO_DEFAULT = 'dumb';

    public static function tableName()
    {
        return 'server';
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function scenarioActions()
    {
        return [
            'default' => 'set-hardware-settings',
            'set-units' => 'set-hardware-settings',
            'set-rack-no' => 'set-hardware-settings',
        ];
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [
                [
                    'summary',
                    'order_no',
                    'brand',
                    'box',
                    'cpu',
                    'ram',
                    'motherboard',
                    'hdd',
                    'hotswap',
                    'raid',
                    'units',
                    'note',
                    'cage_no',
                    'rack_no',
                    'datacenter',
                    'comment',
                ],
                'string',
            ],
            [['id', 'units'], 'required', 'on' => 'set-units'],
            [['id', 'rack_no'], 'required', 'on' => 'set-rack-no'],
        ];
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'summary' => Yii::t('hipanel:server', 'Hardware Summary'),
            'order_no' => Yii::t('hipanel:server', 'Order number'),
            'units' => Yii::t('hipanel:server', 'Units'),
            'motherboard' => Yii::t('hipanel:server', 'Motherboard'),
        ]);
    }
}
