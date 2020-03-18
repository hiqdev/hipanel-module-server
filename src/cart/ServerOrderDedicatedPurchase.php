<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

use hipanel\base\ModelTrait;
use hipanel\modules\finance\cart\PendingPurchaseException;
use hipanel\widgets\Box;
use Yii;
use yii\helpers\Html;

/**
 * Class ServerOrderPurchase.
 *
 * @property string $reservation_id
 */
class ServerOrderDedicatedPurchase extends AbstractServerPurchase
{
    use ModelTrait;

    /** {@inheritdoc} */
    public static function operation()
    {
        return 'BuyConfig';
    }

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->amount = $this->position->getQuantity();
        $this->reservation_id = $this->calculation_id;
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['reservation_id', 'image', 'config_id', 'tariff_id'], 'required'],
            [['reservation_id', 'image', 'administration', 'softpack', 'label', 'location'], 'string'],
            [['tariff_id', 'object_id'], 'integer'],
        ]);
    }

    public function execute()
    {
        if (parent::execute()) {
            $remark = Box::widget([
                'options' => ['class' => 'box-solid box-warning'],
                'body' => Yii::t('hipanel:server:order', '{tnx_for_order} The server will be ready in a few minutes. You will receive an email with server access information right after setup. Go to the {server_list} to see the status of the service preparation.', [
                    'tnx_for_order' => Html::tag('h4', Yii::t('hipanel:server:order', 'Thank you for the order!')),
                    'server_list' => Html::a(Yii::t('hipanel:server:order', 'server list'), ['@server/index']),
                ]),
            ]);

            Yii::$app->getView()->params['remarks'][__CLASS__] = $remark;

            if (is_array($this->_result) && isset($this->_result['_action_pending'])) {
                throw new PendingPurchaseException(Yii::t('hipanel:server:order', 'Server setup will be performed as soon as manager confirms your account verification. Pleas wait.'), $this);
            }

            return true;
        }

        return false;
    }

    public function renderNotes()
    {
    }
}
