<?php
declare(strict_types=1);
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models\traits;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\server\forms\AssignHubsForm;
use hipanel\modules\server\forms\AssignSwitchesForm;
use hipanel\modules\server\models\AssignSwitchInterface;
use hipanel\modules\server\models\Binding;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\widgets\combo\HubCombo;
use Yii;

trait AssignSwitchTrait
{
    private const DEFAULT = 'default';
    private array $sets = [
        AssignSwitchesForm::class => [
            self::DEFAULT => 'net,kvm,pdu,rack,console,location',
            'kvm' => 'net,rack,kvm,pdu',
            'rack,region' => '',
            'location' => 'region,location',
        ],
        AssignHubsForm::class => [
            self::DEFAULT => 'net,net2,kvm,rack,pdu,pdu2,ipmi,jbod',
            'uplink1,uplink2,uplink3,total' => 'net,rack,location',
            'stock' => 'location',
            'chwbox' => 'rack',
        ],
    ];

    public static function fromOriginalModel(AssignSwitchInterface $originalModel): AssignSwitchInterface
    {
        $attributes = array_merge($originalModel->getAttributes(), []);
        /** @var Hub $model */
        $model = new static(['scenario' => 'default']);
        foreach ($originalModel->bindings as $binding) {
            $attribute = $binding->typeWithNo . '_id';
            if ($model->hasAttribute($attribute)) {
                $attributes[$binding->typeWithNo . '_id'] = $binding->switch_id;
                $attributes[$binding->typeWithNo . '_port'] = $binding->port;
            }
            $model->populateRelation('bindings', [$binding->type => $binding]);
        }
        $model->setAttributes($attributes);

        return $model;
    }

    public function defaultSwitchRules(): array
    {
        $variantIds = [];
        $variantPorts = [];
        $allPossibleBindings = [];
        foreach ($this->getActualSets() as $commaSeparatedBindings) {
            $allPossibleBindings = [...$allPossibleBindings, ...ArrayHelper::csplit($commaSeparatedBindings)];
        }

        foreach (array_unique($allPossibleBindings) as $variant) {
            $variantIds[] = $variant . '_id';
            $variantPorts[] = $variant . '_port';
        }

        return [
            [['id'], 'required'],
            [$variantIds, 'integer'],
            [$variantPorts, 'string'],
        ];
    }

    /**
     * For compatibility with [[hiqdev\hiart\Collection]].
     *
     * @param $defaultScenario
     * @param array $data
     * @param array $options
     *
     * @return mixed
     */
    public function batchQuery($defaultScenario, $data = [], array $options = [])
    {
        $map = [
            'update' => 'assign-hubs',
        ];
        $scenario = $map[$defaultScenario] ?? $defaultScenario;

        return parent::batchQuery($scenario, $data, $options);
    }

    /**
     * This method decides which `assigns` will be offered in the form based on the type of the current model
     *
     * @return array
     */
    public function getSwitchVariants(): array
    {
        $bindings = [];
        $sets = $this->getActualSets();
        foreach ($sets as $commaSeparatedTypes => $commaSeparatedBindings) {
            if (isset($this->type) && in_array($this->type, ArrayHelper::csplit($commaSeparatedTypes))) {
                $bindings = empty($commaSeparatedBindings) ? [] : ArrayHelper::csplit($commaSeparatedBindings);
                break;
            }
            $bindings = ArrayHelper::csplit($sets[self::DEFAULT]);
        }

        return $bindings;
    }

    public function getActualSets(): array
    {
        return $this->sets[static::class];
    }

    /**
     * Added to model's rules list of switch pairs.
     *
     * @return array
     */
    protected function generateUniqueValidators(): array
    {
        return array_map(
            fn($variant) => [
                [$variant . '_id', $variant . '_port'],
                fn($attribute, $params, $validator) => $this->validateSwitchVariants($attribute, $variant),
            ],
            $this->getSwitchVariants(),
        );
    }

    protected function validateSwitchVariants($attribute, $variant): void
    {
        if (empty($this->{$attribute}) || empty($this->{$variant . '_id'})) {
            return;
        }
        if ($variant === HubCombo::JBOD) {
            if ($this->{$variant . '_id'} !== $this->id) {
                return;
            }
            $message = "can't connect jbod to himself";
        } else {
            $binding = Binding::find()
                ->andWhere(['port' => $this->{$variant . '_port'}])
                ->andWhere(['switch_id' => $this->{$variant . '_id'}])
                ->andWhere(['ne', 'base_device_id', $this->id])
                ->one();
            if (empty($binding)) {
                return;
            }

            if (!strcmp((string)$this->id, (string)$binding->device_id)) {
                return;
            }
            $message = '{switch}::{port} already taken by {device}';
        }

        $this->addError(
            $attribute,
            Yii::t('hipanel:server', $message, [
                'switch' => $binding->switch_name,
                'port' => $binding->port,
                'device' => $binding->device_name,
            ])
        );
    }
}
