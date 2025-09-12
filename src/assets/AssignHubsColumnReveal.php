<?php

declare(strict_types=1);


namespace hipanel\modules\server\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class AssignHubsColumnReveal extends AssetBundle
{
    private const string FILE_NAME = 'assign-hubs-column-reveal.js';
    public $sourcePath = __DIR__ . '/js';
    public $js = [self::FILE_NAME];
    public $depends = [JqueryAsset::class];
    public $publishOptions = ['only' => [self::FILE_NAME]];
}
