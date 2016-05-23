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

use hipanel\modules\server\helpers\ServerHelper;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\models\Package;
use hipanel\modules\server\widgets\cart\OrderPositionDescriptionWidget;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class ServerOrderProduct extends AbstractServerProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = 'hipanel\modules\server\cart\ServerOrderPurchase';

    /** @var Package */
    protected $_model;

    /** {@inheritdoc} */
    protected $_calculationModel = OrderCalculation::class;

    /**
     * @var Osimage the selected OS image detailed information
     */
    protected $_image;

    /**
     * @var integer
     */
    public $tariff_id;

    /**
     * @var integer id of cluster. See [[hipanel\modules\server\models\Package::getLocations()]] for details
     * @see hipanel\modules\server\models\Package::getLocations()
     */
    public $cluster_id;

    /**
     * @var string the purpose for the server
     */
    public $purpose;

    /**
     * @var string link to any kind of social network
     */
    public $social;

    /**
     * @var string osimage name. Is used to load [[_image]] on product initialisation
     */
    public $osimage;

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
        $this->_model = ServerHelper::getAvailablePackages(null, $this->tariff_id);
        if ($this->_model === null) {
            throw new InvalidConfigException('Failed to find tariff');
        }

        $this->_image = Osimage::find()->where(['osimage' => $this->osimage])->one();
        if ($this->_image === null) {
            throw new InvalidConfigException('Failed to find osimage');
        }

        $this->name = $this->_model->getName();
        $this->description = Yii::t('hipanel/server', 'Order');
    }

    /** {@inheritdoc} */
    public function getId()
    {
        return hash('crc32b', implode('_', ['server', 'order', $this->_model->id]));
    }

    /** {@inheritdoc} */
    public function getCalculationModel($options = [])
    {
        return parent::getCalculationModel(array_merge([
            'tariff_id' => $this->tariff_id,
        ], $options));
    }

    /** {@inheritdoc} */
    public function getPurchaseModel($options = [])
    {
        $this->ensureRelatedData(); // To get fresh domain expiration date
        return parent::getPurchaseModel(array_merge([
            'osimage' => $this->osimage,
            'panel' => $this->_image->getPanelName(),
            'cluster_id' => $this->cluster_id,
            'social' => $this->social,
            'purpose' => $this->purpose,
            'tariff_id' => $this->tariff_id,
        ], $options));
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['cluster_id', 'tariff_id'], 'integer'],
            [['social', 'osimage'], 'safe'],
            [['tariff_id', 'purpose', 'osimage'], 'required'],
            [['cluster_id'], 'validateClusterId'],
        ]);
    }

    public function validateClusterId($value)
    {
        return in_array($this->cluster_id, array_keys($this->_model->getLocations()), true);
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'cluster_id' => Yii::t('hipanel/server/order', 'Server location'),
            'purpose' => Yii::t('hipanel/server/order', 'Purpose'),
            'social' => Yii::t('hipanel/server/order', 'Social network'),
        ]);
    }

    /** {@inheritdoc} */
    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeHints(), [
            'purpose' => Yii::t('hipanel/server/order', 'How are you going to use the server?'),
            'social' => Yii::t('hipanel/server/order', 'Any social network link. Will be used in case of emergency contact'),
        ]);
    }

    /** {@inheritdoc} */
    public function renderDescription()
    {
        return OrderPositionDescriptionWidget::widget(['position' => $this]);
    }

    /**
     * @return Osimage
     */
    public function getImage()
    {
        return $this->_image;
    }
}
