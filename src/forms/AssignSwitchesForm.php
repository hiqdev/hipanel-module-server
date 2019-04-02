<?php

namespace hipanel\modules\server\forms;

use hipanel\base\ModelTrait;
use hipanel\modules\server\models\AssignSwitchInterface;
use hipanel\modules\server\models\Hub;

class AssignSwitchesForm extends Hub implements AssignSwitchInterface
{
    use ModelTrait;

    public $switchVariants = ['net', 'kvm', 'pdu', 'rack', 'console'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hub';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), $this->defaultSwitchRules(), $this->generateUniqueValidators());
    }
}
