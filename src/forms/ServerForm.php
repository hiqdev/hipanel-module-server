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
use hipanel\modules\server\models\Server;
use hipanel\modules\server\validators\MacValidator;
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
        return new self(array_merge($server->getAttributes(), ['server' => $server->name, 'scenario' => 'update'], [
            'ips' => implode(',', ArrayHelper::getColumn($server->ips, 'ip')),
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            // Create/update servers
            [['server', 'type', 'state'], 'required', 'on' => ['create', 'update']],
            [['server', 'dc'], 'unique', 'on' => ['create']],
            [
                ['server'], 'unique', 'on' => ['update'], 'when' => function ($model) {
                    if ($model->isAttributeChanged('server')) {
                        return self::findOne($model->id)->name !== $model->server;
                    }

                    return false;
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
}
