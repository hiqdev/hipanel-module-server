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
    public function fillForm(Example $data)
    {
        $I = $this->tester;
        foreach ($data as $field => $value) {
            if ($value) {
                switch (true) {
                    case in_array($field, ['type_id']):
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
        }
    }

    public function needPageByName(string $name): self
    {
        $this->tester->click("//td/a[contains(text(), '{$name}')]");
        $this->waitPjax();

        return $this;
    }

    public function submitForm(): self
    {
        $this->tester->pressButton('Save');
        $this->waitPjax();
        $this->hasNotErrors();

        return $this;
    }

    public function hasNotErrors(): self
    {
        $this->tester->dontSeeElement("//*[contains(@class, 'has-error')]");

        return $this;
    }

    public function hasErrors(): self
    {
        $this->tester->seeElement("//*[contains(@class, 'has-error')]");

        return $this;
    }

    public function check($data): self
    {
        foreach ($data as $field => $value) {
            if ($value) {
                $this->tester->see($value);
            }
        }

        return $this;
    }

    public function waitPjax(): self
    {
        $this->tester->waitForJS("return $.active == 0;", 30);

        return $this;
    }
}
