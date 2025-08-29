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

use hipanel\base\ModelTrait;
use hipanel\helpers\ArrayHelper;
use hipanel\modules\server\models\AssignHubsInterface;
use hipanel\modules\server\models\Binding;
use hipanel\modules\server\models\Device;
use hipanel\modules\server\models\Hub;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\widgets\combo\HubCombo;
use Yii;

/**
 * Class AssignHubsForm.
 *
 * @property-read array $actualSets
 * @property-read array $hubVariants
 */
class AssignHubsForm extends Device
{
    use ModelTrait;

    private const string DEFAULT = 'default';
    private const int MAX_HUBS_COUNT = 10;
    private static string $modelClass = Server::class;
    private array $sets = [
        Hub::class => [
            self::DEFAULT => 'net,kvm,pdu,pdu*,rack,console,location',
            'kvm' => 'net,rack,kvm,pdu,pdu*',
            'rack,region' => '',
            'location' => 'region,location',
        ],
        Server::class => [
            self::DEFAULT => 'net,net*,kvm,rack,pdu,pdu*,ipmi,jbod',
            'uplink1,uplink2,uplink3,total' => 'net,rack,location',
            'stock' => 'location',
            'chwbox' => 'rack',
            'nic' => 'net,pdu',
        ],
    ];

    public static function tableName()
    {
        return self::$modelClass::tableName();
    }

    public static function setModelClass(string $modelClass): void
    {
        self::$modelClass = $modelClass;
    }

    public static function fromOriginalModel(AssignHubsInterface $originalModel): AssignHubsInterface
    {
        $bindings = [];
        self::$modelClass = $originalModel::class;
        $attributes = $originalModel->getAttributes();
        /** @var Hub $model */
        $model = new static();
        foreach ($originalModel->bindings as $hub) {
            $attribute = $hub->typeWithNo . '_id';
            if ($model->hasAttribute($attribute)) {
                $attributes[$hub->typeWithNo . '_id'] = $hub->switch_id;
                $attributes[$hub->typeWithNo . '_port'] = $hub->port;
                $bindings[$hub->typeWithNo] = $hub;
            }
        }
        $model->populateRelation('hubs', $bindings);
        $model->setAttributes($attributes);

        return $model;
    }

    public function rules(): array
    {
        $defaultSwitchRules = $this->buildDefaultRules();
        $uniqueValidators = $this->buildUniqueValidators();

        return array_merge(
            parent::rules(),
            $defaultSwitchRules,
            $uniqueValidators,
            [
                [['switch'], 'string'],
            ],
        );
    }

    public function attributeLabels(): array
    {
        static $extraLabels = null;
        if ($extraLabels === null) {
            $extraLabels = $this->buildExtraLabels();
        }

        return array_merge(
            parent::attributeLabels(),
            $extraLabels,
            [
                'rack_id' => Yii::t('hipanel:server', 'Rack'),
                'net' => Yii::t('hipanel:server', 'Switch'),
                'kvm' => Yii::t('hipanel:server', 'KVM'),
                'pdu' => Yii::t('hipanel:server', 'APC'),
                'jbod' => Yii::t('hipanel:server', 'JBOD'),
                'ipmi' => Yii::t('hipanel:server', 'IPMI'),
            ]
        );
    }

    /**
     * This method decides which `assigns` will be offered in the form based on the type of the current model
     *
     * @return array
     */
    public function getHubVariants(): array
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

    private function expandHubsList(array $uniqueVariants): array
    {
        // Ensure we work on a copy and keep the original order while avoiding duplicates
        $result = $uniqueVariants;
        $keys = array_flip($result);

        // For each base hub that needs extras, add base{n} for n = 2..MAX_HUBS_COUNT
        foreach (['net*', 'pdu*'] as $base) {
            if (in_array($base, $uniqueVariants, true)) {
                // remove the wildcard entry (e.g. 'net*' or 'pdu*') from the main list
                if (isset($keys[$base])) {
                    // remove from keys map
                    unset($keys[$base]);
                    // remove from result preserving order
                    $result = array_values(array_filter($result, fn($v) => $v !== $base));
                }

                $baseName = rtrim($base, '*'); // 'net*' -> 'net'
                // expand to base2..baseN (keep base (without number) as-is if present in original array)
                for ($i = 2; $i <= self::MAX_HUBS_COUNT; $i++) {
                    $extra = $baseName . $i;
                    if (!isset($keys[$extra])) {
                        $result[] = $extra;
                        $keys[$extra] = true;
                    }
                }
            }
        }

        return $result;
    }

    private function getActualSets(): array
    {
        $result = [];
        $baseSet = $this->sets[self::$modelClass];
        foreach ($baseSet as $commaSeparatedTypes => $commaSeparatedBindings) {
            $possibleBindings = ArrayHelper::csplit($commaSeparatedBindings);
            $result[$commaSeparatedTypes] = implode(", ", $this->expandHubsList(array_unique($possibleBindings)));
        }

        return $result;
    }

    private function buildDefaultRules(): array
    {
        $variantIds = [];
        $variantPorts = [];
        $allPossibleBindings = [];
        $sets = $this->getActualSets();

        foreach ($sets as $commaSeparatedBindings) {
            $allPossibleBindings = [...$allPossibleBindings, ...ArrayHelper::csplit($commaSeparatedBindings)];
        }

        foreach (array_unique($allPossibleBindings) as $variant) {
            $variantIds[] = $variant . '_id';
            $variantPorts[] = $variant . '_port';
        }

        return [
            [['id'], 'required', 'on' => ['assign-hubs']],
            [['hubs'], 'safe', 'on' => ['assign-hubs']],
            [$variantIds, 'integer'],
            [$variantIds, 'default', 'value' => ''],
            [$variantPorts, 'string'],
            [$variantPorts, 'default', 'value' => ''],
        ];
    }

    /**
     * Added to the model's rules list of switch pairs.
     */
    private function buildUniqueValidators(): array
    {
        return array_map(
            function ($variant) {
                if ($variant === HubCombo::JBOD) {
                    return [
                        [$variant . '_id'],
                        function ($attribute) use ($variant) {
                            if ($this->{$variant . '_id'} !== $this->id) {
                                return;
                            }
                            $this->addError(
                                $attribute,
                                Yii::t('hipanel:server', "Can't connect jbod to himself")
                            );
                        },
                    ];
                }

                return [
                    [$variant . '_port'],
                    fn($attribute, $params, $validator) => $this->validateSwitchVariants($attribute, $variant),
                ];
            },
            $this->getHubVariants(),
        );
    }

    private function validateSwitchVariants($attribute, $variant): void
    {
        if (empty($this->{$attribute}) || empty($this->{$variant . '_id'})) {
            return;
        }

        /** @var Binding $binding */
        $binding = Binding::find()
                          ->andWhere(['port' => $this->{$attribute}])
                          ->andWhere(['switch_id' => $this->{$variant . '_id'}])
                          ->andWhere(['ne', 'base_device_id', $this->id])
                          ->one();

        if (empty($binding)) {
            return;
        }

        if (!strcmp((string)$this->id, (string)$binding->device_id)) {
            return;
        }

        $this->addError(
            $attribute,
            Yii::t('hipanel:server', '{switch}::{port} already taken by {device}', [
                'switch' => $binding->switch_name ?? null,
                'port' => $binding->port ?? null,
                'device' => $binding->device_name ?? null,
            ])
        );
    }

    private function buildExtraLabels(): array
    {
        $attributes = $this->getHubVariants();
        $rename = ['net' => 'NIC', 'pdu' => 'APC'];
        $result = [];

        foreach ($attributes as $item) {
            if (preg_match('/^(net|pdu)(\d*)$/', $item, $m)) {
                $type = $m[1];
                $num = $m[2] !== '' ? (int)$m[2] : '';
                $key = $type . ($num === 1 ? '' : $num);

                $result[$key] = Yii::t('hipanel:server', $rename[$type] . $num);
            }
        }

        return $result;
    }
}
