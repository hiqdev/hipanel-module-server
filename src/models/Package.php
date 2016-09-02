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

use hipanel\modules\server\cart\Tariff;
use hipanel\modules\stock\models\Part;
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

    /** @var Part[] */
    public $parts = [];

    /** @var array */
    public $calculation;

    /**
     * @var
     */
    protected $_resources;

    public function init()
    {
        if (empty($this->parts)) {
            foreach ($this->getTariff()->resources as $resource) {
                if (isset($resource->part)) {
                    $this->parts[$resource->part->id] = $resource->part;
                }
            }
        }
    }

    /**
     * @param Tariff $tariff
     */
    public function setTariff($tariff)
    {
        $this->_tariff = $tariff;
    }

    /**
     * @throws InvalidConfigException
     * TODO: implement and get rid of many magic functions bellow
     */
    protected function initResources()
    {
        foreach ($this->_tariff->resources as $resource) {
            $id = $resource->id;

            $this->_resources[$id] = Yii::createObject([
                'class' => $this->buildResourceClass($resource),
                'resource' => $resource,
            ]);
        }
    }

    /**
     * @param $resource
     * @return string
     * TODO: implement and get rid of many magic functions bellow
     */
    protected function buildResourceClass($resource)
    {
        if ($this->parts[$resource->object_id]) {
            return 'yii\base\Object';
        } else {
            return 'yii\base\Object';
        }
    }

    /**
     * @return Tariff
     */
    public function getTariff()
    {
        return $this->_tariff;
    }

    protected function getResourceValue_panel()
    {
        $result = Yii::t('hipanel/server/order', 'No panel / {hipanelLink}', ['hipanelLink' => 'HiPanel']); // todo: add faq link
        if ($this->getResourceByType('isp5')->quantity > 0) {
            $result .= ' / ' . Yii::t('hipanel/server/order', 'ISP manager');
        }

        return $result;
    }

    protected function getResourceTitle_panel()
    {
        return Yii::t('hipanel/server/order', 'Control panel');
    }

    protected function getResourceValue_purpose()
    {
        return Yii::t('hipanel/server/order/purpose', $this->_tariff->label);
    }

    protected function getResourceTitle_purpose()
    {
        return Yii::t('hipanel/server/order', 'Purpose');
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->calculation['value']['usd']['value'];
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
     * @param string $type
     * @throws InvalidConfigException
     * @return mixed
     */
    public function getResourceValue($type)
    {
        $method = 'getResourceValue_' . $type;

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }

        throw new InvalidConfigException("Resource type \"$type\" is not described in the Package");
    }

    /**
     * @param string $type
     * @throws InvalidConfigException
     * @return string
     */
    public function getResourceTitle($type)
    {
        $method = 'getResourceTitle_' . $type;
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }

        throw new InvalidConfigException("Resource type \"$type\" is not described in the Package");
    }

    /**
     * @param $type
     * @throws InvalidConfigException
     * @return array|null
     */
    public function getOverusePrice($type)
    {
        $method = 'getResourceOveruse_' . $type;
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }

        throw new InvalidConfigException("Overuse getter for resource type \"$type\" is not described in the Package");
    }

    /**
     * @param string $type
     * @return Part|null
     */
    public function getPartByType($type)
    {
        foreach ($this->parts as $part) {
            if ($part->model_type === $type) {
                return $part;
            }
        }

        return null;
    }

    /**
     * @param string $type
     * @return Resource|null
     */
    public function getResourceByType($type)
    {
        foreach ($this->_tariff->resources as $resource) {
            if ($resource->type === $type) {
                return $resource;
            }
        }

        return null;
    }

    /**
     * @param string $type
     * @return resource|null
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
        return [
            1 => Yii::t('hipanel/server/order', 'Netherlands, Amsterdam'),
        ];
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
