<?php

namespace app\widgets\imageInput;

use yii\base\Exception;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;
use app\widgets\imageInput\assets\ImageInputAsset;

class ImageInputWidget extends Widget {

    public $model;
    public $placeHolder;
    public $attribute;
    public $htmlOptions = [];
    private $view;
    private $context;
    private $id;
    private $src;

    protected function hasModel() {
        return $this->model instanceof Model &&
            $this->attribute !== null;
    }

    public function init() {
        parent::init();
        ImageInputAsset::register(\Yii::$app->view);

        $this->view = $this->getView();
        $this->context = $this->view->context->id;
        $this->id = $this->context . '-' . $this->attribute;
        $attribute = $this->attribute;
        $this->src = $this->model->isNewRecord || strlen($this->model->$attribute) < 1 ? $this->placeHolder : $this->model->$attribute;
    }

    public function run() {
        if (!$this->hasModel()) {
            throw new Exception('Model must be set');
        }


        return $this->render('imageInput', [
                'htmlOptions' => $this->htmlOptions,
                'model' => $this->model,
                'attribute' => $this->attribute,
                'view' => $this->view,
                'context' => $this->context,
                'id' => $this->id,
                'src' => $this->src,
        ]);
    }

}
