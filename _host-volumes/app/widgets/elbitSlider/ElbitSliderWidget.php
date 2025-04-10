<?php

namespace app\widgets\ElbitSlider;

use yii\base\Widget;
use app\widgets\elbitSlider\assets\ElbitSliderAsset;

class ElbitSliderWidget extends Widget {


    public $name = 'people';
    public $items = [];

    public function init() {
        parent::init();
        ElbitSliderAsset::register(\Yii::$app->view);
    }

    public function run() {
        return $this->render('elbitSlider', [
            'name' => $this->name,
            'items' => $this->items,
        ]);
    }

}