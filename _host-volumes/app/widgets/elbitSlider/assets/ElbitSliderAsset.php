<?php

namespace app\widgets\elbitSlider\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ElbitSliderAsset extends AssetBundle {

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
    public $sourcePath = '@app/widgets/elbitSlider/assets';
    public $css = [
        'css/elbitSlider.css',
    ];
    public $js = [
        'js/mobileEvent.js',
        'js/elbitSlider.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
