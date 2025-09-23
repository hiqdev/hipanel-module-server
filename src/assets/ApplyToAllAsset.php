<?php

declare(strict_types=1);


namespace hipanel\modules\server\assets;

use yii\web\JqueryAsset;

class ApplyToAllAsset extends ServerAsset
{
    private const string FILE_NAME = 'js/apply-to-all.js';
    public $js = [self::FILE_NAME];
    public $depends = [JqueryAsset::class];
}
