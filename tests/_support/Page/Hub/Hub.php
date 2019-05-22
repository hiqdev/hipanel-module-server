<?php

namespace hipanel\modules\server\tests\_support\Page\Hub;

use Codeception\Example;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Textarea;

abstract class Hub extends Authenticated
{
    /**
     * @param Example $data
     * @return Hub
     * @throws \Exception
     */
    public function fillForm(Example $data)
    {
        $I = $this->tester;
        foreach ($data as $field => $value) {
            if (is_null($value)) {
                continue;
            }
            switch (true) {
                case in_array($field, ['type_id', 'nic_media', 'digit_capacity_id']):
                    (new Dropdown($I, "select[name$=\"[{$field}]\"]"))->setValue($value);
                    break;
                case in_array($field, ['note']):
                    (new Textarea($I, "textarea[name$=\"[{$field}]\"]"))->setValue($value);
                    break;
                case in_array($field, ['net_id', 'kvm_id', 'pdu_id', 'rack_id', 'pdu2_id', 'nic2_id', 'ipmi_id', 'location_id']):
                    (new Select2($I, "select[name$=\"[{$field}]\"]"))->setValue($value);
                    break;
                default:
                    (new Input($I, "input[name$=\"[{$field}]\"]"))->setValue($value);
            }
        }

        return $this;
    }

    /**
     * @return Hub
     * @throws \Codeception\Exception\ModuleException
     */
    public function submitForm(): self
    {
        $this->tester->pressButton('Save');
        $this->tester->waitForPageUpdate();

        return $this;
    }

    /**
     * @return Hub
     * @throws \Codeception\Exception\ModuleException
     */
    public function hasNotErrors(): self
    {
        $this->tester->waitForPageUpdate();
        $this->tester->dontSeeElement("//*[contains(@class, 'has-error')]");

        return $this;
    }

    /**
     * @return Hub
     */
    public function hasErrors(): self
    {
        $this->tester->seeElement("//*[contains(@class, 'has-error')]");

        return $this;
    }
}
