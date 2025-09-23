<?php

declare(strict_types=1);


namespace hipanel\modules\server\assets;

use yii\web\JqueryAsset;

class AssignHubsColumnReveal extends ServerAsset
{
    private const string FILE_NAME = 'js/assign-hubs-column-reveal.js';
    public $js = [self::FILE_NAME];
    public $depends = [JqueryAsset::class];
}
