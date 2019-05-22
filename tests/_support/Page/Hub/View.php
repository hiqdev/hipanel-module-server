<?php

namespace hipanel\modules\server\tests\_support\Page\Hub;

use Codeception\Example;
use hipanel\tests\_support\Page\Authenticated;

class View extends Authenticated
{
    /**
     * @param string $action
     */
    public function clickAction(string $action): void
    {
        $selector = "//div[contains(@class, 'box-widget')]//ul//" .
                    "a[contains(text(), '{$action}')]";
        $this->tester->click($selector);
    }

    /**
     * @param Example $data
     */
    public function check(Example $data): void
    {
        foreach ($data as $field => $value) {
            if ($value) {
                $this->tester->see($value);
            }
        }
    }
}
