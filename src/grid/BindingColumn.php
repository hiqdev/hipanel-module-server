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

        return $this->renderSwitchPort($binding);
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
        $defaultLabel = $this->getHeaderCellLabel();
        $transformedLabel = $this->transformLabel($defaultLabel);
        $additionalLink = $this->buildAdditionalLink();

        return $transformedLabel . $additionalLink;
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

        return match (true) {
            $this->attribute === 'ipmi' => $this->createAdditionalLink('@hub/view', 'ipmi'),
            preg_match('/^net|pdu\d+$/', $this->attribute) === 1 => $this->createAdditionalLink(
                '@server/view',
                str_replace(['net', 'pdu'], 'nic', $this->attribute)
            ),
            default => '',
        };
    }

    /**
     * Creates additional link HTML
     */
    private function createAdditionalLink(string $route, string $suffix): string
    {
        $linkText = $this->deviceName . $suffix;
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
