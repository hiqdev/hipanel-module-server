<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

use hipanel\base\ModelTrait;
use hipanel\modules\finance\cart\PendingPurchaseException;
use Yii;

/**
 * Class ServerOrderPurchase.
 */
class ServerOrderPurchase extends AbstractServerPurchase
{
    use ModelTrait;

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'Buy';
    }

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->amount = $this->position->getQuantity();
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['osimage', 'cluster_id', 'purpose', 'tariff_id'], 'required'],
            [['osimage', 'panel', 'social', 'purpose'], 'safe'],
            [['tariff_id', 'cluster_id'], 'integer'],
        ]);
    }

    public function execute()
    {
        if (parent::execute()) {
            Yii::$app->getView()->params['remarks'][] = Yii::t('hipanel/server/order', 'You will receive an email with server access information right after setup.');

            if (is_array($this->_result) && isset($this->_result['_action_pending'])) {
                throw new PendingPurchaseException(Yii::t('hipanel/server/order', 'Server setup will be performed as soon as manager confirms your account verification. Please wait.'), $this->position);
            }

            return true;
        }

        return false;
    }

    public function renderNotes()
    {

    }
}
