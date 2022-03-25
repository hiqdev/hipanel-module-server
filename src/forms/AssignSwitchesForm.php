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
use hipanel\modules\server\models\AssignSwitchInterface;
use hipanel\modules\server\models\Hub;
use Yii;

class AssignSwitchesForm extends Hub
{
    use ModelTrait;

    protected array $switchVariants = ['net', 'kvm', 'pdu', 'rack', 'console', 'location'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hub';
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
            'rack_id' => Yii::t('hipanel:server', 'Rack'),
        ]);
    }
}
