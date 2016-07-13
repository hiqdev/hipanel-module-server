<?php

/*
 * HiPanel tickets module
 *
 * @link      https://github.com/hiqdev/hipanel-module-ticket
 * @package   hipanel-module-ticket
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
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
