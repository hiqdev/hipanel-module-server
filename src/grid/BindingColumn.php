<?php

declare(strict_types=1);


namespace hipanel\modules\server\grid;

use hipanel\grid\DataColumn;
use hipanel\helpers\StringHelper;
use hipanel\modules\server\models\Binding;
use Yii;
use yii\helpers\Html;

class BindingColumn extends DataColumn
{
    public $format = 'raw';
    public $filter = false;
    public $encodeLabel = false;
    public ?string $deviceName = null;
    public ?int $deviceId = null;

    private const string CABLING_ATTRIBUTE = '/^(net|pdu)(\d*)$/';
    private const string NIC_TARGET = '/^(.+)nic(\d+)$/';
    private const string NIC_NO = 'NIC {no}';
    private const string POWER_NO = 'Power {no}';
    private const string VIA_DEVICE = 'via {device}';
    private const string HUB_PORT = '{hub} port {port}';
    private const string HUB_SOCKET = '{hub} socket {port}';
    private const string HUB_NIC = '{hub} NIC {no}';
    private const string NOTES = 'Notes: {notes}';

    public function init(): void
    {
        parent::init();
        $this->visible = Yii::$app->user->can('hub.read');
        $this->label = $this->buildLabel();
    }

    public function getDataCellValue($model, $key, $index): string
    {
        if ($this->isIPMIAttribute()) {
            return $this->renderIPMIBinding($model);
        }

        $binding = $model->bindings[$this->attribute] ?? null;
        if ($binding !== null && $this->isCabling()) {
            return $this->renderCabling($binding);
        }

        return $this->renderSwitchPort($binding);
    }

    private function renderCabling(Binding $binding): string
    {
        $inn = $this->formatINN($binding->switch_inn);
        $link = Html::a(Html::encode($this->describeTarget($binding)), ['@hub/view', 'id' => $binding->switch_id]);

        return "$inn&rarr; <b>$link</b>" . $this->renderNotes($binding);
    }

    private function renderNotes(Binding $binding): string
    {
        if ($binding->switch_label === null || $binding->switch_label === '') {
            return '';
        }

        return Html::tag('div', Html::encode(Yii::t('hipanel:server', self::NOTES, ['notes' => $binding->switch_label])), ['class' => 'text-muted']);
    }

    private function describeTarget(Binding $binding): string
    {
        $hub = (string)$binding->switch;
        if (preg_match(self::NIC_TARGET, $hub, $nic) === 1) {
            $hub = Yii::t('hipanel:server', self::HUB_NIC, ['hub' => $nic[1], 'no' => $nic[2]]);
        }
        if ($binding->port === null || $binding->port === '') {
            return $hub;
        }

        return Yii::t('hipanel:server', $this->isPower() ? self::HUB_SOCKET : self::HUB_PORT, ['hub' => $hub, 'port' => $binding->port]);
    }

    private function isCabling(): bool
    {
        return $this->hasRequiredLinkData() && preg_match(self::CABLING_ATTRIBUTE, $this->attribute) === 1;
    }

    private function isPower(): bool
    {
        return str_starts_with($this->attribute, 'pdu');
    }

    private function cablingNo(): int
    {
        preg_match(self::CABLING_ATTRIBUTE, $this->attribute, $match);

        return $match[2] === '' ? 1 : (int)$match[2];
    }

    /**
     * Renders IPMI binding with device IP link and switch port information
     */
    private function renderIPMIBinding($model): string
    {
        $ipmi = $model->getBinding($this->attribute);
        if ($ipmi === null) {
            return '';
        }

        $deviceLink = isset($ipmi->device_ip) ? $this->createDeviceIpLink($ipmi->device_ip) : '';
        $switchPortInfo = $this->renderSwitchPort($ipmi);

        return $deviceLink . $switchPortInfo;
    }

    /**
     * Creates a clickable link for the device IP address
     */
    private function createDeviceIpLink(string $deviceIp): string
    {
        $encodedIp = Html::encode($deviceIp);

        return Html::a($encodedIp, "http://$encodedIp/", ['target' => '_blank']) . ' ';
    }

    /**
     * Renders switch port information with proper formatting
     */
    protected function renderSwitchPort(?Binding $binding): string
    {
        if ($binding === null) {
            return '';
        }

        $inn = $this->formatINN($binding->switch_inn);
        $switchPortLink = $this->createSwitchPortLink($binding);
        if ($this->hasRequiredLinkData()) {
            return "$inn<b>$switchPortLink</b>" . $this->renderNotes($binding);
        }
        $label = Html::encode($binding->switch_label);

        return "$inn<b>$switchPortLink</b> $label";
    }

    /**
     * Formats the INN (tax identification number) with parentheses
     */
    private function formatINN(?string $inn): string
    {
        if (empty($inn)) {
            return '';
        }

        return Html::encode("($inn) ");
    }

    /**
     * Creates a link to the switch/hub view page
     */
    private function createSwitchPortLink(Binding $binding): string
    {
        $switchText = $binding->switch;
        if ($binding->port) {
            $switchText .= ":$binding->port";
        }

        return Html::a(Html::encode($switchText), ['@hub/view', 'id' => $binding->switch_id]);
    }

    /**
     * Builds the column label with transformations and additional links
     */
    private function buildLabel(): string
    {
        if ($this->isCabling()) {
            return $this->buildCablingLabel();
        }

        $defaultLabel = $this->getHeaderCellLabel();
        $transformedLabel = $this->transformLabel($defaultLabel);
        $additionalLink = $this->buildAdditionalLink();

        return $transformedLabel . $additionalLink;
    }

    private function buildCablingLabel(): string
    {
        $no = $this->cablingNo();
        $label = Html::encode(Yii::t('hipanel:server', $this->isPower() ? self::POWER_NO : self::NIC_NO, ['no' => $no]));
        if ($no === 1) {
            return $label;
        }
        $nicName = $this->deviceName . 'nic' . $no;
        $via = Yii::t('hipanel:server', self::VIA_DEVICE, ['device' => Html::a(Html::encode($nicName), ['@server/view', 'id' => $this->deviceId])]);

        return "$label<br/>($via)";
    }

    /**
     * Applies label transformations (add colons, replace terms)
     */
    private function transformLabel(string $label): string
    {
        // Add colons before numbers
        $labelWithColons = preg_replace('/(\d+)/', ':${1}', $label);

        // Replace Net/Pdu with Switch/APC
        return (string)preg_replace(['/Net/', '/Pdu/'], ['Switch', 'APC'], $labelWithColons);
    }

    /**
     * Builds an additional link based on an attribute type
     */
    private function buildAdditionalLink(): string
    {
        if (!$this->hasRequiredLinkData()) {
            return '';
        }

        return $this->attribute === 'ipmi' ? $this->createAdditionalLink('@hub/view', 'ipmi') : '';
    }

    /**
     * Creates additional link HTML
     */
    private function createAdditionalLink(string $route, string $suffix): string
    {
        $linkText = str_contains($this->deviceName, $suffix) ? $this->deviceName : $this->deviceName . $suffix;
        $link = Html::a($linkText, [$route, 'id' => $this->deviceId]);

        return '<br/>' . $link;
    }

    /**
     * Checks if the current attribute is an IPMI attribute
     */
    private function isIPMIAttribute(): bool
    {
        return StringHelper::startsWith($this->attribute, 'ipmi', false);
    }

    /**
     * Checks if we have the required data to build additional links
     */
    private function hasRequiredLinkData(): bool
    {
        return $this->deviceId !== null && $this->deviceName !== null;
    }
}
