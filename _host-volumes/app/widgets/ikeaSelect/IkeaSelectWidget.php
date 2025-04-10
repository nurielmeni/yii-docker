<?php

namespace app\widgets\ikeaSelect;

use yii\base\Exception;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;
use app\widgets\ikeaSelect\assets\IkeaSelectAsset;

class IkeaSelectWidget extends Widget {

    public $model;
    public $formName;
    public $attribute;
    public $items = [];
    public $inputOptions = [];
    public $wraperOptions = [];
    public $prompt;
    private $view;
    private $context;
    private $id;

    protected function hasModel() {
        return $this->model instanceof Model &&
            $this->attribute !== null;
    }

    public function init() {
        parent::init();
        IkeaSelectAsset::register(\Yii::$app->view);

        $this->view = $this->getView();
        $this->formName = $this->model->formName();
        $this->context = $this->view->context->id;
        $this->inputOptions['id'] = strtolower($this->formName) . '-' . $this->attribute;
        $this->inputOptions['name'] = $this->formName . '[' . $this->attribute . ']';
        $attribute = $this->attribute;
        if (is_array($this->wraperOptions) && array_key_exists('class', $this->wraperOptions)) {
            $this->wraperOptions['class'] = strstr($this->wraperOptions['class'], 'form-group') ? '' : ' form-group';
        } else {
            $this->wraperOptions['class'] = 'form-group';
        }
        
        $fieldClass = 'field-' . $this->inputOptions['id'];
        $this->wraperOptions['class'] .= strstr($this->wraperOptions['class'], $fieldClass) ? '' : ' ' . $fieldClass;
        $this->wraperOptions['class'] = trim($this->wraperOptions['class']);
    }

    public function run() {
        if (!$this->hasModel()) {
            throw new Exception('Model must be set');
        }


        return $this->render('ikeaSelect', [
                'wraperOptions' => $this->wraperOptions,
                'inputOptions' => $this->inputOptions,
                'model' => $this->model,
                'attribute' => $this->attribute,
                'items' => $this->items,
                'view' => $this->view,
        ]);
    }

}
