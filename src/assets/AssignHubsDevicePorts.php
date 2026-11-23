<?php

declare(strict_types=1);

namespace hipanel\modules\server\assets;

use yii\bootstrap\BootstrapPluginAsset;
use yii\web\JqueryAsset;

class AssignHubsDevicePorts extends ServerAsset
{
    private const string FILE_NAME = 'js/assign-hubs-device-ports.js';
    public $js = [self::FILE_NAME];
    public $depends = [JqueryAsset::class, BootstrapPluginAsset::class];
}
