<?php

namespace app\widgets\ikeaSelect\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class IkeaSelectAsset extends AssetBundle {

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
    public $sourcePath = '@app/widgets/ikeaSelect/assets';
    public $css = [
        'css/nice-select.css',
    ];
    public $js = [
        'js/jquery.nice-select.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
