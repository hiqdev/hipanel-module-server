<?php

/*
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

use hipanel\modules\finance\logic\TariffCalculator;
use hipanel\modules\finance\models\Calculation;
use hipanel\modules\finance\models\ServerResource;
use hipanel\modules\finance\models\stubs\ServerResourceStub;
use hipanel\modules\finance\models\Tariff;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Class Package is a wrapper for [[Tariff]], its parts, resources and calculation of its price.
 * @property string $name
 */
class Package extends Model
{
    /**
     * @var string CRC32 temporary uniq id of the package
     */
    private $_id;

    /** @var Tariff */
    protected $_tariff;

    /** @var Calculation */
    public $calculation;

    /**
     * @param Tariff $tariff
     */
    public function setTariff($tariff)
    {
        $this->_tariff = $tariff;
    }

    /**
     * @return Tariff
     */
    public function getTariff()
    {
        return $this->_tariff;
    }

    public function getStubResource($type)
    {
        return new ServerResourceStub([
            'tariff' => $this->_tariff,
            'type' => $type
        ]);
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->calculation->forCurrency($this->getTariff()->currency)->value;
    }

    /**
     * @throws InvalidConfigException
     * @return string
     */
    public function getDisplayPrice()
    {
        return Yii::t('hipanel/server/order', '{price}/mo', [
            'price' => Yii::$app->formatter->asCurrency($this->getPrice(), Yii::$app->params['currency']),
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_tariff->name;
    }

    /**
     * @param $type
     * @return \hipanel\modules\finance\models\DomainResource|ServerResource|Resource
     */
    public function getResourceByType($type)
    {
        return $this->_tariff->getResourceByType($type);
    }

    /**
     * @param string $type
     * @return ServerResource|null
     */
    public function getResourceByModelType($type)
    {
        foreach ($this->_tariff->resources as $resource) {
            if ($resource->model_type === $type) {
                return $resource;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getLocations()
    {
        $data = [
            Tariff::TYPE_XEN => [
                1 => Yii::t('hipanel/server/order', 'Netherlands, Amsterdam'),
                2 => Yii::t('hipanel/server/order', 'USA, Ashburn'),
            ],
            Tariff::TYPE_OPENVZ => [
                2 => Yii::t('hipanel/server/order', 'USA, Ashburn'),
                3 => Yii::t('hipanel/server/order', 'Netherlands, Amsterdam'),
            ],
        ];

        return $data[$this->_tariff->type];
    }

    /**
     * @return string
     */
    public function getId()
    {
        if (!isset($this->_id)) {
            $this->_id = hash('crc32b', implode('_', ['server', 'order', uniqid()]));
        }

        return $this->_id;
    }
}
