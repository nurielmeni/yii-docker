<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;
use app\widgets\imageInput\ImageInputWidget;
use kartik\date\DatePicker;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Campaign */
/* @var $form yii\widgets\ActiveForm */
?>

<?php 
    $this->registerJs('q_quill_1.format("header", 2);',
            View::POS_READY);
?>

<div class="campaign-form col-xs-12 col-sm-6">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sid')->dropDownList($model->supplierOptions) ?>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'start_date')->widget(DatePicker::class, \Yii::$app->params['datePickerKvOptions']) ?>
        </div>
    
        <div class="col-xs-6">
            <?= $form->field($model, 'end_date')->widget(DatePicker::class, \Yii::$app->params['datePickerKvOptions']) ?>
        </div>
    </div>

    <hr>

    <?= $form->field($model, 'campaign')->widget(\bizley\quill\Quill::className(), [
        'toolbarOptions' => [
            ['bold', 'italic', 'underline'], 
            [['color' => []], ['background' => []]],
            [['align' => []]],
            [['direction' => 'rtl']],
            [[ 'size' => ['small', false, 'large', 'huge'] ]],
            [[ 'header' => [1, 2, 3, 4, 5, 6, false] ]],
        ],
        'options' => [
            'style' => 'direction: rtl; background-color: #eeeeee;',
        ],
        'formats' => ['header' => 2],
    ]) ?>
    
    <hr>
    
    
    <hr>
    <?= $form->field($model, 'image')->widget(ImageInputWidget::class, [
            'htmlOptions' => ['style' => 'cursor: pointer;'],
            'placeHolder' => 'images/image-placeholder.svg',
        ]);
    ?>

    <?= $form->field($model, 'mobile_image')->widget(ImageInputWidget::class, [
            'htmlOptions' => ['style' => 'cursor: pointer;'],
            'placeHolder' => 'images/image-placeholder.svg',
        ]);
    ?>

    <?= $form->field($model, 'logo')->widget(ImageInputWidget::class, [
            'htmlOptions' => ['style' => 'cursor: pointer;'],
            'placeHolder' => 'images/image-placeholder.svg',
        ]);
    ?>

    <?= $form->field($model, 'youtube_video_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

    <hr>
    
    <?= $form->field($model, 'tag_header')->textarea(['rows' => '6', 'style' => 'direction: ltr;', 'placeholder' => Yii::t('app', '*** JS code only, without the <script></script> tags (for security)')]) ?>
    
    <?= $form->field($model, 'tag_body')->textarea(['rows' => '3', 'style' => 'direction: ltr;']) ?>

    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>
