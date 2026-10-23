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

use hipanel\base\Module;
use hipanel\modules\finance\providers\ConsumptionsProvider;
use hipanel\modules\server\controllers\ServerController;

class ServerControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ServerController $object;

    protected function setUp(): void
    {
        if (!class_exists(\Yii::class)) {
            require_once \Composer\InstalledVersions::getInstallPath('yiisoft/yii2') . '/Yii.php';
        }
        if (\Yii::$app === null) {
            new \yii\web\Application([
                'id' => 'test',
                'basePath' => sys_get_temp_dir(),
                'components' => [
                    'request' => ['cookieValidationKey' => 'test'],
                    'i18n' => ['translations' => ['*' => ['class' => \yii\i18n\PhpMessageSource::class]]],
                ],
            ]);
        }
        $this->object = new ServerController('test', new Module('server', null), $this->createMock(ConsumptionsProvider::class));
    }

    protected function tearDown(): void
    {
        \Yii::$app->errorHandler->unregister();
    }

    public function testActions(): void
    {
        $this->assertIsArray($this->object->actions());
    }
}
