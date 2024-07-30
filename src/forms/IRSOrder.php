<?php declare(strict_types=1);

namespace hipanel\modules\server\forms;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\server\models\Irs;
use hipanel\modules\ticket\models\Thread;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class IRSOrder extends Model
{
    public string $location = '';
    public string $config = '';
    public bool $upgrade = false;
    public string $ram = '';
    public string $raid = '';
    public string $hdd = '';
    public string $ssd = '';
    public string $ip = '';
    public string $administration = '';
    public string $os = '';
    public string $diskPartitioningComment = '';
    public string $traffic_tb = '';
    public string $traffic_mbps = '';
    public string $ipmi = '';
    public string $licencesOrSupport = '';
    public string $projectInfo = '';
    public string $comment = '';
    public string $currency = '';
    public string $price = '';
    private ?Irs $server = null;
    private array $pricingAttributes = ['ip', 'administration', 'os', 'ram', 'raid', 'ssd', 'hdd', 'ipmi', 'traffic_tb', 'traffic_mbps'];

    public function rules(): array
    {
        return [
            [['upgrade'], 'boolean'],
            [
                [
                    'comment',
                    'ipmi',
                    'ip',
                    'location',
                    'config',
                    'os',
                    'administration',
                    'traffic_tb',
                    'traffic_mbps',
                    'diskPartitioningComment',
                    'licencesOrSupport',
                    'projectInfo',
                    'ram',
                    'raid',
                    'hdd',
                    'ssd',
                    'price',
                ],
                'string',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ip' => Yii::t('hipanel.server.irs', 'IP'),
            'config' => Yii::t('hipanel.server.irs', 'HW Configuration'),
            'upgrade' => Yii::t('hipanel.server.irs', 'Upgrade/Changes are needed'),
            'os' => Yii::t('hipanel.server.irs', 'Operating system'),
            'ipmi' => Yii::t('hipanel.server.irs', 'IPMI'),
            'projectInfo' => Yii::t('hipanel.server.irs', 'Information about the project'),
            'ram' => Yii::t('hipanel.server.irs', 'RAM - Total capacity'),
            'raid' => Yii::t('hipanel.server.irs', 'RAID'),
            'hdd' => Yii::t('hipanel.server.irs', 'HDD'),
            'ssd' => Yii::t('hipanel.server.irs', 'SSD'),
            'diskPartitioningComment' => Yii::t('hipanel.server.irs', 'Disk partitioning comment'),
            'licencesOrSupport' => Yii::t('hipanel.server.irs', 'Additional licences or support '),
            'traffic_tb' => Yii::t('hipanel.server.irs', 'Traffic TB'),
            'traffic_mbps' => Yii::t('hipanel.server.irs', 'Traffic Mbps/Gbps'),
            'price' => Yii::t('hipanel.server.irs', 'Total price'),
        ];
    }

    public function createTicket(): Thread
    {
        $thread = new Thread();
        $thread->subject = 'IRS NEW Order';
        $thread->message = $this->createMessage();
        $thread->priority = 'high';
        $thread->topics = 'technical'; // todo: irs
        $thread->save();

        return $thread;
    }

    public function getItems(string $attribute): array
    {
        return ArrayHelper::map($this->server->irsOptions[$attribute] ?? [], 'Dropdowns', 'Dropdowns');
    }

    public function setServer(?Irs $server): void
    {
        $this->server = $server;
        $this->config = $this->server->hwsummary_auto;
        $this->location = $this->server->locationName;
        $this->currency = $this->server->getActualSale()->currency ?? 'USD';
        $this->price = $this->server->getActualSale()->fee ?? '0';
        foreach ($this->pricingAttributes as $attribute) {
            $this->{$attribute} = $this->getFirstValue($attribute);
        }
        $this->administration = $this->setAdministrationValue();
    }

    public function getServer(): ?Irs
    {
        return $this->server;
    }

    private function getFirstValue(string $attribute): string
    {
        $items = $this->getItems($attribute);

        return $items[array_key_first($items)] ?? '';
    }

    private function createMessage(): string
    {
        $output = [];
        foreach ($this->getAttributes() as $attribute => $value) {
            if ($value === '' || $attribute === 'currency') {
                continue;
            }
            $output[] = '**' . $this->getAttributeLabel($attribute) . ':** ' . (is_bool($value) ? ($value ? 'Yes' : 'No') : nl2br(Html::encode($value)));
        }

        return implode("\n\n", $output);
    }

    public function toOptions(): array
    {
        $options = [];
        foreach ($this->pricingAttributes as $attribute) {
            $discount = 0;
            if (in_array($attribute, ['ram', 'hdd', 'ssd', 'administration'], true)) {
                foreach ($this->server->irsOptions['prices'][$attribute] ?? [] as $price) {
                    $discount += $price['price'];
                }
            }
            foreach ($this->server->irsOptions[$attribute] as $row) {
                if (isset($row['AH price'])) {
                    $price = $row['AH price'] === 'Included' ? $row['AH price'] : preg_replace('/[^0-9.]/', '', (string)$row['AH price']);
                } else {
                    $price = 'Included';
                }
                $options[$attribute][$row['Dropdowns']] = [
                    'price' => $price,
                    'discount' => $price === 'Included' && $attribute !== 'administration' ? 0 : $discount,
                    'hint' => $row['Hint'],
                ];
            }
        }

        return $options;
    }

    private function setAdministrationValue(): string
    {
        $options = $this->getItems('administration');
        $value = $this->server->getAdministrationLabel();
        foreach ($options as $option) {
            if (str_contains($option, $value)) {
                return $option;
            }
        }
    }
}
