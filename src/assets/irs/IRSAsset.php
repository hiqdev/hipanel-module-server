<?php declare(strict_types=1);

namespace hipanel\modules\server\assets\irs;

use hipanel\assets\Vue3CdnAsset;
use yii\web\AssetBundle;

class IRSAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    public $js = ['irs.js'];
    public $css = ['irs.css'];
    public $depends = [
        Vue3CdnAsset::class,
    ];
}
