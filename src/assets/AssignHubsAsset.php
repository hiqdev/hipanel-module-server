<?php


namespace hipanel\modules\server\assets;


use yii\web\AssetBundle;

class AssignHubsAsset extends AssetBundle
{
    /**
     * {@inheridoc}
     */
    public $sourcePath = '@hipanel/modules/server/assets';

    /**
     * {@inheridoc}
     */
    public $js = [
        'js/AssignHubs.js'
    ];

    /**
     * {@inheridoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * {@inheridoc}
     */
    public $publishOptions = [
        'forceCopy' => true,
        'linkAssets' => true,
        'appendTimestamp' => true,
    ];
}
