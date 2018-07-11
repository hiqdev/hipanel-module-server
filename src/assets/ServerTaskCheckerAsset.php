<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\assets;

use yii\web\AssetBundle;

class ServerTaskCheckerAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@hipanel/modules/server/assets';

    /**
     * @var array
     */
    public $js = [
        'js/serverTaskChecker.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'hiqdev\assets\visibilityjs\VisibilityjsAsset',
    ];
}
