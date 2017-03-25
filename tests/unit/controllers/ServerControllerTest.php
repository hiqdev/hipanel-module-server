<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\tests\unit\controllers;

use hipanel\modules\server\controllers\ServerController;

class ServerControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServerController
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ServerController('test', null);
    }

    protected function tearDown()
    {
    }

    public function testActions()
    {
        $this->assertInternalType('array', $this->object->actions());
    }
}
