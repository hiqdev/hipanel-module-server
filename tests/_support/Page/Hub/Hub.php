<?php

namespace hipanel\modules\server\tests\_support\Page\Hub;

use Codeception\Example;
use hipanel\modules\server\tests\_support\Page\AbstractServerForm;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Textarea;

/**
 * Class Hub
 * @package hipanel\modules\server\tests\_support\Page\Hub
 */
abstract class Hub extends AbstractServerForm
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
                case in_array($field, ['net_id', 'kvm_id', 'pdu_id', 'rack_id', 'pdu2_id', 'net2_id', 'ipmi_id', 'location_id']):
                    (new Select2($I, "select[name$=\"[{$field}]\"]"))->setValue($value);
                    break;
                default:
                    (new Input($I, "input[name$=\"[{$field}]\"]"))->setValue($value);
            }
        }

        return $this;
    }
}
