<?php

namespace hipanel\modules\server\tests\_support\Page;

use Codeception\Example;
use hipanel\tests\_support\Page\Authenticated;

/**
 * Class AbstractServerForm
 * @package hipanel\modules\server\tests\_support\Page
 */
abstract class AbstractServerForm extends Authenticated
{
    /**
     * @param Example $data
     * @return AbstractServerForm
     * @throws \Exception
     */
    abstract public function fillForm(Example $data);

    /**
     * @return AbstractServerForm
     * @throws \Codeception\Exception\ModuleException
     */
    public function submitForm(): self
    {
        $this->tester->pressButton('Save');
        $this->tester->waitForPageUpdate();

        return $this;
    }

    /**
     * @return AbstractServerForm
     * @throws \Codeception\Exception\ModuleException
     */
    public function hasNotErrors(): self
    {
        $this->tester->waitForPageUpdate();
        $this->tester->dontSeeElement("//*[contains(@class, 'has-error')]");

        return $this;
    }

    /**
     * @return AbstractServerForm
     */
    public function hasErrors(): self
    {
        $this->tester->seeElement("//*[contains(@class, 'has-error')]");

        return $this;
    }
}
