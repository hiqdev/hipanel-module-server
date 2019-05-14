<?php

namespace hipanel\modules\server\tests\_support\Page\Hub;

class Update extends Hub
{
    public function needUpdatePage()
    {
        $this->tester->seeInCurrentUrl('view?id');
        $this->tester->click('div.box-menu a[href*="update"]');
        $this->waitPjax();
        $this->tester->seeInCurrentUrl('update?id=');
    }
}
