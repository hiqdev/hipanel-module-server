<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

use hipanel\modules\server\models\Server;
use Yii;

class ServerRenewProduct extends AbstractServerProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = 'hipanel\modules\server\cart\ServerRenewPurchase';

    /** {@inheritdoc} */
    protected $_operation = 'renew';

    /** {@inheritdoc} */
    public static function primaryKey()
    {
        return ['model_id'];
    }

    /** {@inheritdoc} */
    public function load($data, $formName = null)
    {
        $result = parent::load($data, '');
        if ($result) {
            $this->loadRelatedData();
        }

        return $result;
    }

    /** {@inheritdoc} */
    private function loadRelatedData()
    {
        $this->_model = Server::findOne(['id' => $this->model_id]);
        $this->name = $this->_model->name;
        $this->description = Yii::t('hipanel/server', 'Renewal');
    }

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['server', 'renew', $this->_model->id]));
    }

    /** {@inheritdoc} */
    public function getCalculationModel($options = [])
    {
        return parent::getCalculationModel(array_merge([
            'id' => $this->model_id,
        ], $options));
    }

    /** {@inheritdoc} */
    public function getPurchaseModel($options = [])
    {
        $this->loadRelatedData(); // To get fresh domain expiration date
        return parent::getPurchaseModel(array_merge(['expires' => $this->_model->expires], $options));
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['model_id'], 'integer'],
        ]);
    }
}
