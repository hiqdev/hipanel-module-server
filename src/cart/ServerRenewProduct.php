<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\cart;

use DateTime;
use hipanel\modules\server\models\Server;
use Yii;
use yii\helpers\Html;

/**
 * Class ServerRenewProduct.
 */
class ServerRenewProduct extends AbstractServerProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = 'hipanel\modules\server\cart\ServerRenewPurchase';

    /** {@inheritdoc} */
    protected $_calculationModel = RenewCalculation::class;

    /** {@inheritdoc} */
    public static function primaryKey()
    {
        return ['model_id'];
    }

    /** {@inheritdoc} */
    public function load($data, $formName = null)
    {
        if ($result = parent::load($data, '')) {
            $this->ensureRelatedData();
        }

        return $result;
    }

    /** {@inheritdoc} */
    private function ensureRelatedData()
    {
        $this->_model = Server::findOne(['id' => $this->model_id]);
        $this->name = $this->_model->name;
        $this->description = Yii::t('hipanel:server', 'Renewal');
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
            'type' => 'renew',
            'server' => $this->name,
            'expires' => $this->_model->expires,
        ], $options));
    }

    /** {@inheritdoc} */
    public function getQuantityOptions()
    {
        $result = [];
        foreach ([1, 3, 6, 12] as $n) {
            $date = (new DateTime($this->_model->expires))->add(new \DateInterval("P{$n}M"));

            $result[$n] = Yii::t('hipanel:server', '{n, plural, one{# month} other{# months}} till {date}', [
                'n' => $n,
                'date' => Yii::$app->formatter->asDate($date),
            ]);
        }

        return $result;
    }

    /** {@inheritdoc} */
    public function getPurchaseModel($options = [])
    {
        $this->ensureRelatedData(); // To get fresh domain expiration date
        return parent::getPurchaseModel(array_merge(['expires' => $this->_model->expires], $options));
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['model_id'], 'integer'],
            [['name'], 'required'],
        ]);
    }

    public function renderDescription()
    {
        return $this->getIcon() . ' ' . Html::a($this->getName(), ['@server/view', 'id' => $this->model_id]) . ' ' . Html::tag('span', $this->getDescription(), ['class' => 'text-muted']);
    }
}
