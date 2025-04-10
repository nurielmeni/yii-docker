<?php

namespace app\widgets\SubmitPopup;

use yii\base\Widget;
use yii\helpers\Url;
use app\widgets\submitPopup\assets\SubmitPopupAsset;

class SubmitPopupWidget extends Widget {


    public $name = 'submit-popup';
    public $campaignId;

    public function init() {
        parent::init();
        SubmitPopupAsset::register(\Yii::$app->view);
    }

    public function run() {
        return $this->render('submitPopup', [
            'name' => $this->name,
            'campaignId' => $this->campaignId,
            'applyUrl' => Url::to('site/apply'),
        ]);
    }

}