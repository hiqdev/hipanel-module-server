<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\assets;

use yii\web\AssetBundle;

class OsSelectionAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__;

    /**
     * @var array
     */
    public $js = [
        'js/OsSelection.js',
    ];

    public $css = [
        'css/OsSelection.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
