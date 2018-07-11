<?php

namespace hipanel\modules\server\forms;

use hipanel\helpers\StringHelper;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\validators\MacValidator;
use yii\helpers\ArrayHelper;

class ServerForm extends Server
{
    use \hipanel\base\ModelTrait;

    public $server;

    public static function tableName()
    {
        return 'server';
    }

    public static function fromServer(Server $server): ServerForm
    {
        return new self(array_merge($server->getAttributes(), ['server' => $server->name, 'scenario' => 'update'], [
            'ips' => implode(',', ArrayHelper::getColumn($server->ips, 'ip')),
        ]));
    }

    public function rules()
    {
        return array_merge(parent::rules(), [

            // Create/update servers
            [['server', 'type'], 'required', 'on' => ['create', 'update']],
            [['server', 'dc'], 'unique', 'on' => ['create']],
            [
                ['server'], 'unique', 'on' => ['update'], 'when' => function ($model) {
                if ($model->isAttributeChanged('server')) {
                    return Server::findOne($model->id)->server !== $model->server;
                }

                return false;
            },
            ],
            [
                ['dc'], 'unique', 'on' => ['update'], 'when' => function ($model) {
                if ($model->isAttributeChanged('dc')) {
                    return Server::findOne($model->id)->dc !== $model->dc;
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

