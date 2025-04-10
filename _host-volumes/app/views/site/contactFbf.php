<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;


$this->title = $campaign->name;

?>

<style>
    .btn-primary {
        background-color:
            <?= empty($campaign->button_color) ? '#dae249' : $campaign->button_color ?>
        ;
    }

    .ikea-fbf-form .image {
        background: url("<?= Url::to('@web/' . $campaign->image) ?>") no-repeat center;
        background-size: cover;
        height: calc(100vh - 346px);
        min-height: 360px;
    }

    @media(max-width:767px) {
        .ikea-fbf-form .image {
            height: 25vh;
            min-height: 0;
        }
    }

    @media(min-width:768px) {}

    .campaign-wrap .ikea-image {
        background: url('<?= Url::to('@web/' . $campaign->image) ?>') no-repeat center center;
        background-size: cover;
    }
</style>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
    <div class="reply-wrapper bg-main hv-100 flex flex-c center">
        <div class="container">
            <div class="row-fluid">
                <div role="alert"
                    class="alert alert-success ikea-title col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                    <h1><?= Yii::t('app', 'Thank you for your request! we will contact you soon') ?></h1>
                    <h2>אגד מחלקת הגיוס</h2>
                </div>
            </div>

            <div class="row">
                <p class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                    <button class="btn btn-info bg-yellow">
                        <?= Html::a('חזור', Url::to('@web/' . $campaign->id)) ?>
                    </button>
                </p>
            </div>
            <p style="text-align: center; font-size: 14px; padding: 6px;">
                <a href="https://niloosoft.com/he/"
                    target="_blank"
                    rel="external"
                    style="text-decoration: none;">
                    POWERED BY NILOOSOFT HUNTER EDGE
                </a>
            </p>
        </div>
    </div>
<?php else: ?>


    <div class="row-fluid flex flex-r flex-wn space-between">
        <div class="wrap-rigth bg-main">
            <div class="ikea-fbf-form flex flex-c center-block">

                <div class="image">
                </div>
                <div class="col-xs-12 bg-main h-100 visible-xs">
                    <div id="campaign">
                        <?= $campaign->campaign ?>
                    </div>
                </div>
                <div class="row form-data">
                    <?php $form = ActiveForm::begin(['action' => ['contact-fbf', 'id' => $campaign->id], 'id' => 'contact-form-fbf', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                    <div id="applicant-form-data">
                        <?= $this->renderAjax('applicant', ['model' => $model, 'campaign' => $campaign, 'form' => $form, 'id' => '0']) ?>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <?= Html::button(Yii::t('app', 'Another Freind'), ['id' => 'another-freind-btn', 'class' => 'btn btn-primary fg-green text-bold']) ?>
                        </div>
                    </div>
                    <div class="row bdb">
                        <div class="col-xs-12 col-md-6 myDetails flex flex-r space-between">

                            <?= $form->field($model, 'myName', ['errorOptions' => ['id' => 'help-myName']])
                                ->textInput([
                                    'placeholder' => $model->getAttributeLabel('myName'),
                                    'autofocus' => 'autofocus',
                                    'aria-label' => $model->getAttributeLabel('myName'),
                                    'aria-describedby' => 'help-myName',
                                ])
                                ->label(false) ?>

                            <?= $form->field($model, 'myNumber', ['errorOptions' => ['id' => 'help-myNumber']])
                                ->textInput([
                                    'placeholder' => $model->getAttributeLabel('myNumber'),
                                    'autofocus' => 'autofocus',
                                    'aria-label' => $model->getAttributeLabel('myNumber'),
                                    'aria-describedby' => 'help-myNumber',
                                ])
                                ->label(false) ?>

                        </div>
                        <div class="form-group col-xs-12 col-md-6"
                            style="padding-left: 0; min-height: auto;">
                            <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary fg-green text-bold col-xs-12 col-sm-6', 'name' => 'contact-button']) ?>
                        </div>
                    </div>
                    <?= $form->field($model, 'supplierId', ['options' => ['class' => 'hidden']])->hiddenInput()->label(false) ?>
                    <?php ActiveForm::end(); ?>

                    <div class="more-jobs col-xs-12 flex flex-r flex-wn space-between">
                        <?= Html::a(Yii::t('app', 'More Jobs'), Yii::$app->params['additionalJobs']) ?>
                        <span class="fg-white"><?= $campaign->contact ?></span>
                    </div>

                </div>

            </div>
        </div>
        <div class="ikea-form flex flex-c">
            <div class="row-fluid logo top text-left bg-white hidden-xs">
                <?= Html::img('@web/' . $campaign->logo, ['width' => '120px', 'alt' => 'לוגו אגד']) ?>
            </div>

            <div class="col-xs-12 bg-main h-100 hidden-xs">
                <div id="campaign">
                    <?= $campaign->campaign ?>
                </div>
            </div>

            <div class="row-fluid logo bottom text-left bg-white visible-xs">
                <?= Html::img('@web/' . $campaign->logo, ['width' => '120px', 'alt' => 'לוגו אגד']) ?>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php
$this->registerJs('$("#another-freind-btn").on("click", function() { $("#applicant-form-data").append($("<div class=\'bdtop\'>").load("' . Url::to('@web/site/applicant') . '", { id: $(".row.applicant-fields").length, del: true }, function() { $("#name" + ($(".row.applicant-fields").length - 1)).focus(); } ));});');
$this->registerJs('$(document).on("click", ".row.applicant-fields button.del", function() { $(this).parents(".bdtop").remove(); });');
$this->registerJs('$(document).on("click", "button[type=\'submit\']", function() { setTimeout(function () {$(".has-error:first > input:first").focus(); }, 500); });');
?>