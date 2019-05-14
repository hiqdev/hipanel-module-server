<?php

namespace hipanel\modules\server\tests\_support\Page\Hub;

use hipanel\helpers\Url;

class Delete extends Hub
{
    public function deleteHub(string $name): self
    {
        $I = $this->tester;

        $I->click("//a[contains(text(), 'Delete')]");
        $I->seeInPopup('Are you sure you want to delete this item?');
        $I->acceptPopup();
        $I->seeInCurrentUrl(Url::to('@hub/index'));
        $I->seeInTitle('Switches');
        $I->cantSee($name);

        return $this;
    }
}
