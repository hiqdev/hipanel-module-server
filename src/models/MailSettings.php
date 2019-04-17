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

class MailSettings extends \hipanel\base\Model
{
    const SCENARIO_DEFAULT = 'dumb';

    public static function tableName()
    {
        return 'server';
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['block_sending'], 'boolean'],
            [['per_hour_limit'], 'number'],
        ];
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function scenarioActions()
    {
        return [
            'default' => 'set-mail-settings',
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'per_hour_limit'   => Yii::t('hipanel:server', 'Mails per hour limit'),
            'block_sending' => Yii::t('hipanel:server', 'Block sending mail'),
        ]);
    }
}
