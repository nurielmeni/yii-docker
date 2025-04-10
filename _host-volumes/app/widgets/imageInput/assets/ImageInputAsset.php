<?php

namespace app\widgets\imageInput\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ImageInputAsset extends AssetBundle {

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
    public $sourcePath = '@app/widgets/imageInput/assets';
    public $css = [
        'css/imageInput.css',
    ];
    public $js = [
        'js/imageInput.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
