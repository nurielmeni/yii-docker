<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Json;

?>

<?php $form = isset($form) ? $form : ActiveForm::begin(['id' => 'contact-form-new', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="row applicant-fields">
    <div class="col-xs-12 col-md-6 applicant flex flex-r space-between">

        <?= $form->field($model, "name[$id]", ['errorOptions' => ['id' => "help-name$id"]])
            ->textInput([
                'id' => 'name' . $id,
                'autofocus' => 'autofocus',
                'placeholder' => $model->getAttributeLabel('name'),
                'aria-label' => $model->getAttributeLabel('name'),
                'aria-describedby' => "help-name$id",
            ])
            ->label(false) ?>

        <?= $form->field($model, "phone[$id]", ['errorOptions' => ['id' => "help-phone$id"]])
            ->textInput([
                'id' => 'phone' . $id,
                'autofocus' => 'autofocus',
                'placeholder' => $model->getAttributeLabel('phone'),
                'aria-label' => $model->getAttributeLabel('phone'),
                'aria-describedby' => "help-phone$id",
            ])
            ->label(false) ?>

    </div>
    <div class="col-xs-12 col-md-6 applicant flex flex-r space-between">
        <?= $form->field($model, "searchArea[$id]", ['errorOptions' => ['id' => "help-searchArea$id"]])
            ->dropDownList($model->searchAreaOptions, [
                'id' => 'searchArea' . $id,
                'autofocus' => 'autofocus',
                'prompt' => $model->getAttributeLabel('searchArea'),
                'aria-label' => $model->getAttributeLabel('searchArea'),
                'aria-describedby' => "help-searchArea$id",
            ])
            ->label(false) ?>

        <div class="form-group attach-file text-left">
            <button class="form-control btn-clear"><label for="<?= 'file' . $id ?>"><i
                        class="glyphicon glyphicon-paperclip"
                        style="margin-left: 10px;"></i><?= $model->getAttributeLabel('cvfile') ?></label></button>
            <div class="hidden">
                <?= $form->field($model, "cvfile[$id]", ['errorOptions' => ['id' => "help-cvfile$id"]], ['options' => ['class' => 'attach-file text-left', 'style' => (isset($del) && $del ? 'padding-left: 0;' : 'padding-left: 10px;') . 'max-width: 130px;', 'aria-label' => $model->getAttributeLabel('cvfile')]])
                    ->fileInput([
                        'id' => 'file' . $id,
                        'autofocus' => 'autofocus',
                        'aria-describedby' => "help-cvfile$id",
                        'accept' => '.pdf,.rtf,.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ])
                    ->label(false) ?>
            </div>

        </div>
        <?= isset($del) && $del ? Html::tag(
            'button',
            '',
            [
                'class' => 'glyphicon glyphicon-trash del btn-primary',
                'style' => 'cursor: pointer; color: #00a77a; height: fit-content;border-radius: 50%; padding: 9px;margin-right: -18px;',
                'aria-label' => 'מחק חבר',
            ]
        ) : Html::tag(
                    'button',
                    '',
                    [
                        'class' => 'glyphicon glyphicon-trash invisible btn-primary'
                    ]
                )
            ?>
    </div>
</div>


<?php if (!isset($form))
    ActiveForm::end(); ?>

<?php
foreach ($form->attributes as $attribute) {
    $attribute = Json::htmlEncode($attribute);
    $this->registerJs("jQuery('form').yiiActiveForm('add', $attribute);", $this::POS_READY);
}
?>