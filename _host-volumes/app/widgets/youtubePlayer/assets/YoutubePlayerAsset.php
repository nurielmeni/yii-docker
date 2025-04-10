<?php

namespace app\widgets\youtubePlayer\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class YoutubePlayerAsset extends AssetBundle {

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
    public $sourcePath = '@app/widgets/youtubePlayer/assets';
    public $css = [
        'css/youtubePlayer.css',
    ];
    public $js = [
        'js/youtubePlayer.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
