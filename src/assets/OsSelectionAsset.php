<?php

namespace hipanel\modules\server\assets;
use yii\web\AssetBundle;

class OsSelectionAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/modules/server/src/assets';

    /**
     * @var array
     */
    public $js = [
        'js/OsSelection.js',
    ];

    public $css = [
        'css/OsSelection.css'
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
