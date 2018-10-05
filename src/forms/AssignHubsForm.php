<?php

namespace hipanel\modules\server\forms;

use hipanel\modules\server\models\Binding;
use hipanel\modules\server\models\Server;
use Yii;
use yii\db\Query;

/**
 * Class AssignHubsForm
 */
class AssignHubsForm extends Server
{
    use \hipanel\base\ModelTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'server';
    }

    /**
     * Create AttachHubsForm model from Server model
     *
     * @param Server $server
     * @return AssignHubsForm
     */
    public static function fromServer(Server $server): AssignHubsForm
    {
        $attributes = array_merge($server->getAttributes(), []);
        $model = new self(['scenario' => 'default']);
        foreach ($server->bindings as $binding) {
            if ($model->hasAttribute($binding->typeWithNo . '_id')) {
                $attributes[$binding->typeWithNo . '_id'] = $binding->switch_id;
                $attributes[$binding->typeWithNo . '_port'] = $binding->port;
            }
        }
        $model->setAttributes($attributes);

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id'], 'required'],
            [['net_id', 'kvm_id', 'pdu_id', 'rack_id', 'pdu2_id', 'nic2_id', 'ipmi_id'], 'integer'],
            [['net_port', 'kvm_port', 'pdu_port', 'rack_port', 'pdu2_port', 'nic2_port', 'ipmi_port'], 'string'],
        ], $this->generateUniqueValidators());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'pdu2' => Yii::t('hipanel:server', 'APC 2'),
            'nic2' => Yii::t('hipanel:server', 'Switch 2'),
            'net_port' => Yii::t('hipanel:server', 'Port'),
            'kvm_port' => Yii::t('hipanel:server', 'Port'),
            'pdu_port' => Yii::t('hipanel:server', 'Port'),
            'rack_port' => Yii::t('hipanel:server', 'Port'),
            'pdu2_port' => Yii::t('hipanel:server', 'Port'),
            'nic2_port' => Yii::t('hipanel:server', 'Port'),
            'impi_port' => Yii::t('hipanel:server', 'Port'),
        ]);
    }

    /**
     * For compatibility with [[hiqdev\hiart\Collection]]
     *
     * @param $defaultScenario
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function batchQuery($defaultScenario, $data = [], array $options = [])
    {
        $map = [
            'update' => 'assign-hubs',
        ];
        $scenario = isset($map[$defaultScenario]) ? $map[$defaultScenario] : $defaultScenario;

        return (new Server)->batchQuery($scenario, $data, $options);
    }

    private function generateUniqueValidators(): array
    {
        $rules = [];
        foreach (['net', 'kmv', 'pdu', 'rack', 'pdu2', 'nic2', 'ipmi'] as $variant) {
            $rules[] = [
                [$variant . '_id', $variant . '_port'],
                'unique', 'targetClass' => Binding::class,
                'targetAttribute' => [
                    $variant . '_port' => 'port', $variant . '_id' => 'switch_id',
                ],
                'filter' => function ($query) {
                    /** @var Query $query */
                    $query->andWhere(['check_only' => true]);
                    $query->andWhere(['ne', 'device_id', $this->id]);
                },
            ];
        }

        return $rules;
    }
}

