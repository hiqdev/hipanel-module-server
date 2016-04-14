<?php

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
    public $parts;

    /** @var array */
    public $calculation;

    /**
     * @var
     */
    protected $_resources;

    public function init()
    {
        //$this->initResources();
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
                'resource' => $resource
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

    protected function getResourceTitle_cpu()
    {
        return Yii::t('hipanel/server', 'CPU');
    }

    protected function getResourceValue_cpu()
    {
        $part = $this->getPartByType('cpu');
        preg_match('/((\d+) cores?)$/i', $part->partno, $matches);
        return Yii::t('hipanel/server', '{0, plural, one{# core} other{# cores}}', $matches[2]);
    }

    protected function getResourceTitle_ram()
    {
        return Yii::t('hipanel/server', 'RAM');
    }

    protected function getResourceValue_ram()
    {
        $part = $this->getPartByType('ram');
        preg_match('/((\d{1,5}) MB)$/i', $part->partno, $matches);
        return Yii::t('yii', '{nFormatted} GB', ['nFormatted' => (int)$matches[2] / 1024]); // Gb
    }

    protected function getResourceOveruse_ram()
    {
        return [
            'price' => Yii::$app->formatter->asCurrency(4, Yii::$app->params['currency']),
            'unit' => Yii::t('yii', '{nFormatted} GB', ['nFormatted' => 1])
        ];
    }

    protected function getResourceTitle_hdd()
    {
        return Yii::t('hipanel/server', 'SSD');
    }

    protected function getResourceValue_hdd()
    {
        $part = $this->getPartByType('hdd');
        preg_match('/((\d{1,5}) GB)$/i', $part->partno, $matches);
        return Yii::t('yii', '{nFormatted} GB', ['nFormatted' => (int)$matches[2]]); // Gb
    }

    protected function getResourceOveruse_hdd()
    {
        // TODO: extract from overuse resource
        return [
            'price' => Yii::$app->formatter->asCurrency(0.2, Yii::$app->params['currency']),
            'unit' => Yii::t('yii', '{nFormatted} GB', ['nFormatted' => 1])
        ];
    }

    protected function getResourceTitle_ip()
    {
        return Yii::t('hipanel/server', 'Dedicated IP');
    }

    protected function getResourceValue_ip()
    {
        return $this->getResourceByType('ip_num')->quantity;
    }

    protected function getResourceOveruse_ip()
    {
        // TODO: extract from overuse resource
        return [
            'price' => Yii::$app->formatter->asCurrency(3.5, Yii::$app->params['currency']),
            'unit' => Yii::t('yii', '{n} IP', ['n' => 1])
        ];
    }

    protected function getResourceTitle_support_time()
    {
        return Yii::t('hipanel/server', '24/7 support');
    }

    protected function getResourceValue_support_time()
    {
        $quantity = $this->getResourceByType('support_time')->quantity;
        if ($quantity == 1) {
            return Yii::t('hipanel/server', 'Bronze');
        } elseif ($quantity == 1.5) {
            return Yii::t('hipanel/server', 'Silver');
        } elseif ($quantity == 2) {
            return Yii::t('hipanel/server', 'Gold');
        } elseif ($quantity == 3) {
            return Yii::t('hipanel/server', 'Platinum');
        } else {
            return Yii::t('hipanel/server', '{n, plural, one{# hour} other {# hours}}', ['n' => $quantity]);
        }
    }

    protected function getResourceOveruse_traffic()
    {
        // TODO: extract from overuse resource
        return [
            'price' => Yii::$app->formatter->asCurrency(0.02, Yii::$app->params['currency']),
            'unit' => Yii::t('yii', '{nFormatted} GB', ['nFormatted' => 1])
        ];
    }

    protected function getResourceTitle_traffic()
    {
        return Yii::t('hipanel/server', 'Traffic');
    }

    protected function getResourceValue_traffic()
    {
        return Yii::t('yii', '{nFormatted} GB',
            ['nFormatted' => $this->getResourceByType('server_traf_max')->quantity]);
    }

    protected function getResourceValue_speed()
    {
        return Yii::t('hipanel/server', '{n} Gbit/s', ['n' => 1]);
    }

    protected function getResourceTitle_speed()
    {
        return Yii::t('hipanel/server', 'Port speed');
    }

    protected function getResourceValue_panel()
    {
        $result = Yii::t('hipanel/server', 'No panel / {hipanelLink}',
            ['hipanelLink' => 'HiPanel']); // todo: add faq link
        if ($this->getResourceByType('isp5')->quantity > 0) {
            $result .= ' / ' . Yii::t('hipanel/server', 'ISP manager');
        }

        return $result;
    }

    protected function getResourceTitle_panel()
    {
        return Yii::t('hipanel/server', 'Control panel');
    }

    protected function getResourceValue_purpose()
    {
        return Yii::t('hipanel/server', $this->_tariff->label); // todo: translate possible labels
    }

    protected function getResourceTitle_purpose()
    {
        return Yii::t('hipanel/server', 'Purpose');
    }


    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->calculation['value']['usd']['value'];
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function getDisplayPrice()
    {
        return Yii::t('hipanel/server', '{price}/mo', [
            'price' => Yii::$app->formatter->asCurrency($this->getPrice(), Yii::$app->params['currency'])
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
     * @return mixed
     * @throws InvalidConfigException
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
     * @return string
     * @throws InvalidConfigException
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
     * @return array|null
     * @throws InvalidConfigException
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
     * @return Resource|null
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
            1 => Yii::t('hipanel/server', 'Netherlands, Amsterdam'),
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
