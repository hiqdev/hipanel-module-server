<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\forms;

use hipanel\helpers\StringHelper;
use hipanel\modules\hosting\models\Ip;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\validators\MacValidator;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ServerForm represent create/update form.
 */
class ServerForm extends Server
{
    use \hipanel\base\ModelTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'server';
    }

    /**
     * Create ServerForm model from Server model.
     *
     * @param Server $server
     * @return ServerForm
     */
    public static function fromServer(Server $server): ServerForm
    {
        $ips = [];
        if (is_iterable($server->ips)) {
            $ips = self::setMainIpToBegining(self::getIpsFomServer($server->ips), $server->getAttribute('ip'));
        }

        return new self(array_merge(
            $server->getAttributes(),
            ['server' => $server->name, 'new_server_name' => $server->name, 'scenario' => 'update'],
            ['ips' => implode(',', $ips)]
        ));
    }

    /**
     * @param Ip[]|string[] $ips
     * @return string[]
     */
    private static function getIpsFomServer(array $ips): array
    {
        $items = [];
        foreach ($ips as $ip) {
            if ($ip instanceof Ip) {
                $items[] = $ip->ip;
            } else {
                $items[] = $ip;
            }
        }

        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            // Create/update servers
            [['server', 'type'], 'required', 'on' => ['update']],
            [
                ['state'], 'required', 'on' => ['update'], 'when' => function () {
                    $existing = self::find()->byId($this->id)->includeDeleted()->one();

                    return $existing && !$existing->isDeleted();
                },
            ],
            [['server', 'type', 'state'], 'required', 'on' => ['create']],
            [['new_server_name'], 'required', 'on' => 'update'],
            [['server', 'new_server_name'], 'string', 'max' => 63, 'min' => 1],
            [['server', 'dc'], 'unique', 'on' => ['create']],
            [
                ['new_server_name'], 'unique', 'on' => ['update'], 'when' => function () {
                    $existing = self::find()->where(['eq', 'server', $this->new_server_name])->one();

                    return $existing && (string)$existing->id !== (string)$this->id && $existing->name === $this->new_server_name;
                },
            ],
            [
                ['dc'], 'unique', 'on' => ['update'], 'when' => function ($model) {
                    if ($model->isAttributeChanged('dc')) {
                        return self::findOne($model->id)->dc !== $model->dc;
                    }

                    return false;
                },
            ],
            [['name', 'dc', 'label', 'order_no', 'hwsummary'], 'string', 'on' => ['create', 'update']],
            [['mac'], MacValidator::class, 'on' => ['create', 'update']],
            [
                ['ips'], 'filter',
                'filter' => function ($value) {
                    return (mb_strlen($value) > 0) ? StringHelper::mexplode($value) : '';
                },
                'on' => ['create', 'update'],
            ],
            [['ips'], 'each', 'rule' => ['ip'], 'on' => ['create', 'update']],
        ]);
    }

    private static function setMainIpToBegining(array $ips, ?string $mainIp): array
    {
        $keyIp = array_search($mainIp, $ips);
        if ($keyIp !== false) {
            unset($ips[$keyIp]);
            array_unshift($ips, $mainIp);
        }

        return $ips;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'new_server_name' => Yii::t('hipanel:server', 'Name'),
        ]);
    }
}
