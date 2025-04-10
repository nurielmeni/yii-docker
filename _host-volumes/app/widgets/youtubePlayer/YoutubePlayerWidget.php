<?php

namespace app\widgets\YoutubePlayer;

use yii\base\Widget;
use app\widgets\youtubePlayer\assets\YoutubePlayerAsset;

class YoutubePlayerWidget extends Widget {


    public $name = 'player';
    public $height = 390;
    public $width = 640;
    public $videoId = 'M7lc1UVf-VE';
    public $playButtonId = 'player-play-button';
    public $playerVars = [
        'playsinline' => 1
    ];

    public function init() {
        parent::init();
        YoutubePlayerAsset::register(\Yii::$app->view);
    }

    public function run() {
        return $this->render('youtubePlayer', [
            'name' => $this->name,
            'height' => $this->height,
            'width' => $this->width,
            'videoId' => $this->videoId,
            'playButtonId' => $this->playButtonId,
            'playerVars' => $this->playerVars,
        ]);
    }

}