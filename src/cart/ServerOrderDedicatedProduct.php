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

use DateTimeImmutable;
use hipanel\modules\server\models\Config;
use hipanel\modules\server\models\Osimage;
use hipanel\modules\server\widgets\cart\OrderPositionDescriptionWidget;
use hiqdev\hiart\ResponseErrorException;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class ServerOrderDedicatedProduct extends AbstractServerProduct
{
    /** {@inheritdoc} */
    protected $_purchaseModel = ServerOrderDedicatedPurchase::class;

    /** @var Config */
    protected $_model;

    /** {@inheritdoc} */
    protected $_calculationModel = ConfigCalculation::class;

    /** {@inheritdoc} */
    protected $duration = [1];

    /**
     * @var Osimage the selected OS image detailed information
     */
    protected $_image;

    /**
     * @var string
     */
    public $location;

    /**
     * @var integer
     */
    public $object_id;

    /**
     * @var integer
     */
    public $tariff_id;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string link to any kind of social network
     */
    public $administration;

    /**
     * @var string osimage name. Is used to load [[_image]] on product initialisation
     */
    public $osimage;

    /**
     * @var string software package name
     */
    public $softpack;

    /**
     * @var DateTimeImmutable
     */
    public $expirationTime;

    /** {@inheritdoc} */
    public function load($data, $formName = null)
    {
        if ($result = parent::load($data, '')) {
            $this->ensureRelatedData();
        }

        return $result;
    }

    /** {@inheritdoc} */
    protected function ensureRelatedData()
    {
        $availableConfig = Config::find(['batch' => true])->getAvailable()->withPrices()->withSellerOptions()->andWhere(['id' => $this->object_id])->limit(1)->createCommand()->send()->getData();
        if (empty($availableConfig)) {
            throw new InvalidConfigException('Failed to find config');
        }
        $config = new Config();
        $config->setAttributes(reset($availableConfig));
        $this->_model = $config;

        $this->_image = Osimage::find()->where(['osimage' => $this->osimage, 'type' => 'dedicated'])->one();
        if ($this->_image === null) {
            throw new InvalidConfigException('Failed to find osimage');
        }
        $this->name = $this->_model->name;
        $this->description = $this->_model->descr;
        $this->reserve();
    }

    public function reserve(): void
    {
        try {
            $reserve = Config::perform('reserve', [
                'id' => $this->object_id,
                'location' => $this->location,
                'reservation_id' => $this->getId(),
            ]);
        } catch (ResponseErrorException $e) {
            throw new \RuntimeException(Yii::t('hipanel:server', 'Failed to reserve a server configuration: most probably it is out of stock now'));
        }

        $this->expirationTime = (new DateTimeImmutable($reserve['expirationTime']));
    }

    /** {@inheritdoc} */
    public function getId()
    {
        if ($this->_id === null) {
            $this->_id = hash('crc32b', implode('_', ['server', 'order', 'dedicated', $this->_model->id, mt_rand()]));
        }

        return $this->_id;
    }

    /** {@inheritdoc} */
    public function getCalculationModel($options = [])
    {
        return parent::getCalculationModel(array_merge([
            'tariff_id' => $this->tariff_id,
            'object_id' => $this->object_id,
            'location' => $this->location,
            'image' => $this->osimage,
        ], $options));
    }

    /** {@inheritdoc} */
    public function getPurchaseModel($options = [])
    {
        $this->ensureRelatedData();

        $options = array_merge([
            'image' => $this->osimage,
            'config_id' => $this->object_id,
            'label' => $this->label,
            'administration' => $this->administration,
            'softpack' => $this->softpack,
            'tariff_id' => $this->tariff_id,
            'location' => $this->location,
        ], $options);

        return parent::getPurchaseModel($options);
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['object_id', 'tariff_id'], 'integer'],
            [['administration', 'osimage', 'label', 'location'], 'string'],
            [['tariff_id', 'object_id', 'osimage', 'location'], 'required'],
        ]);
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'object_id' => Yii::t('hipanel:server:order', 'Server config'),
            'label' => Yii::t('hipanel:server:order', 'Label'),
        ]);
    }

    /** {@inheritdoc} */
    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeHints(), [
            'label' => Yii::t('hipanel:server:order', 'How are you going to use the server?'),
            'administration' => Yii::t('hipanel:server:order', 'Any social network link. Will be used in case of emergency contact'),
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

    protected function serializationMap()
    {
        $parent = parent::serializationMap();
        $parent['object_id'] = $this->object_id;
        $parent['osimage'] = $this->osimage;
        $parent['label'] = $this->label;
        $parent['administration'] = $this->administration;
        $parent['softpack'] = $this->softpack;
        $parent['tariff_id'] = $this->tariff_id;
        $parent['location'] = $this->location;
        $parent['expirationTime'] = $this->expirationTime;
        $parent['_image'] = $this->_image;

        return $parent;
    }

    public function getDisplayAdministration(): string
    {
        $map = [
            'unmanaged' => Yii::t('hipanel:server:order', 'Basic maintenance'),
            'managed' => Yii::t('hipanel:server:order', 'Expert service 24/7'),
        ];

        return $map[$this->administration] ?? 'unknown state of administration';
    }

    public function getDisplayLocation(): string
    {
        $map = [
            'nl' => Yii::t('hipanel:server:order', 'Netherlands'),
            'us' => Yii::t('hipanel:server:order', 'USA'),
        ];

        return $map[$this->location] ?? 'unknown place';
    }

    public function getAdditionalLinks(): array
    {
        return [
            [
                Yii::$app->params['module.server.order.frontend.url'] ?? '/server/order/dedicated',
                Yii::t('hipanel:server:order', 'Order another server')
            ],
        ];
    }
}
