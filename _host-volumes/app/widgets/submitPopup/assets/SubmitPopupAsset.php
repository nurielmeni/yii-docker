<?php

namespace app\widgets\submitPopup\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SubmitPopupAsset extends AssetBundle {

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
    public $sourcePath = '@app/widgets/submitPopup/assets';
    public $css = [
        'css/loader.css',
        'css/submitPopup.css',
    ];
    public $js = [
        'js/jquery.validate.min.js',
        'js/submitPopup.js',
        'js/messages_he.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
