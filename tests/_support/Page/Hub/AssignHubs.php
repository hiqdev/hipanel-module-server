<?php

namespace hipanel\modules\server\tests\_support\Page\Hub;

use Codeception\Example;

class AssignHubs extends Hub
{
    public function needGoToAssignForm(string $linkName = 'assign-switches')
    {
        $this->tester->seeInCurrentUrl('view?id');
        $this->tester->click("div.box-menu a[href*=\"{$linkName}\"]");
        $this->waitPjax();
        $this->tester->seeInCurrentUrl("{$linkName}");
    }

    public function fillAssignForm(Example $data): self
    {
        $this->fillForm($data);

        return $this;
    }

    public function checkAssigned(Example $data): self
    {
        $this->tester->seeInCurrentUrl('view?id');
        $this->check($data);

        return $this;
    }
}
