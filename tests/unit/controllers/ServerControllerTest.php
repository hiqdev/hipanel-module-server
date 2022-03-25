<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\tests\unit\controllers;

use hipanel\modules\server\controllers\ServerController;

class ServerControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ServerController $object;

    protected function setUp(): void
    {
        $this->object = new ServerController('test', null);
    }

    protected function tearDown(): void
    {
    }

    public function testActions(): void
    {
        $this->assertIsArray($this->object->actions());
    }
}
