<?php

namespace hipanel\modules\server\tests\_support\Page\Hub;

use hipanel\tests\_support\Page\Authenticated;

class View extends Authenticated
{
    public function clickAction(string $action)
    {
        $selector = "//div[contains(@class, 'box-widget')]//ul//" .
                    "a[contains(text(), '{$action}')]";
        $this->tester->click($selector);
    }
}
