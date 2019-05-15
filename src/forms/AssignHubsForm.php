<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\forms;

use hipanel\base\ModelTrait;
use hipanel\modules\server\models\Server;
use Yii;

/**
 * Class AssignHubsForm.
 */
class AssignHubsForm extends Server
{
    use ModelTrait;

    /**
     * @var array
     */
    public $switchVariants = ['net', 'kvm', 'pdu', 'rack', 'pdu2', 'nic2', 'ipmi'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'server';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), $this->defaultSwitchRules(), $this->generateUniqueValidators());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'pdu2' => Yii::t('hipanel:server', 'APC 2'),
            'nic2' => Yii::t('hipanel:server', 'Switch 2'),
        ]);
    }
}
