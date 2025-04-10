<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use app\widgets\ikeaSelect\IkeaSelectWidget;

$this->title = $campaign->name;

// Google tag manager head
$js = "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-59CMPPZ');";

//// Register header and body scripts
//// To bypass site restriction to save script data
//$this->registerJs($campaign->tag_header, $this::POS_HEAD);
//$this->registerJs($campaign->tag_body, $this::POS_BEGIN);


$js = "$('button[type=\"submit\"]').on('click', function() {
    if ($('form#contact-form .has-error').length === 0) {
    	console.log('tag submit');
        dataLayer.push({'Category':'Ikea דרושים','Action':'Form Sent','Label':'Success' ,'event':'auto_event'});
    }
});";
$this->registerJs($js, $this::POS_READY);
?>
<?php $this->beginBlock('tag_header'); ?>
<?= $campaign->tag_header ?>
<?php $this->endBlock(); ?>

<style>
    .btn.btn-primary {
        background-color:
            <?= empty($campaign->button_color) ? '#FFDB00' : $campaign->button_color ?>
        ;
    }

    .campaign-wrap .ikea-image {
        background: url('<?= Url::to('@web/' . $campaign->image) ?>') no-repeat top center;
        background-size: cover;
        min-height: 30vh;
    }
</style>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <?= $campaign->tag_body ?>

    <div class="reply-wrapper bg-main hv-100 flex flex-c center">
        <div class="container"
            style="margin-top: 150px;">
            <div class="row">
                <div role="alert"
                    class="alert alert-success ikea-title col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                    <h3><?= Yii::t('app', 'Thank you for your request! we will contact you soon') ?></h3>
                    <h4><?= Yii::t('app', 'Just before you leave, pick are facebook') ?></h4>
                    <p>
                        <?= Yii::t('app', 'Redirect to are facebook page') ?>
                        <?= Html::a(Yii::t('app', 'press here'), Yii::$app->params['faceBook'], ['class' => 'fb-icon']) ?>
                    </p>
                </div>
            </div>

            <div class="row actions">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                    <?= Html::a(Yii::t('app', 'More Jobs'), Yii::$app->params['additionalJobs'], ['class' => 'btn btn-default bg-white fg-blue text-bold']) ?>
                    <button class="btn bg-yellow fg-blue text-bold">
                        <?= Html::a('חזור', Url::to('@web/' . $campaign->id)) ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>

    <div id="ikea-wrapper"
        class="row-fluid h-100 flex flex-r flex-ww space-between">
        <div class="w-100 visible-xs">
            <?= Html::img('@web/' . $campaign->logo, ['style' => 'width: 18%; position: absolute; top: 15px;left: 15px;', 'alt' => 'לוגו איקאה', 'class' => 'visible-xs']) ?>
            <!---- Campaign Image Placeholder ---->
            <?= Html::img('@web/' . $campaign->image, ['alt' => 'Ikea Campaign Image', 'width' => '100%', 'class' => '']) ?>
        </div>
        <div class="ikea-image col-md-8 col-sm-7 col-xs-12 hidden-xs">
            <!---- Campaign Image Placeholder ---->
        </div>
        <div class="ikea-form flex flex-c col-md-4 col-sm-5 col-xs-12">
            <div class="row-fluid logo text-left bg-main hidden-xs">
                <div class="col-xs-12">
                    <?= Html::img('@web/' . $campaign->logo, ['width' => '38%', 'alt' => 'לוגו איקאה']) ?>
                </div>
            </div>

            <div class="row-fluid bg-blue h-100 fields">

                <div id="campaign"
                    class="col-xs-12">
                    <?= $campaign->campaign ?>
                </div>

                <div class="ikea-form col-xs-12">
                    <?php $form = ActiveForm::begin(['id' => 'contact-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                    <?= $form->field($model, 'name', ['errorOptions' => ['id' => 'help-name'], 'options' => ['class' => 'form-group']])
                        ->textInput([
                            'autofocus' => true,
                            'placeholder' => $model->getAttributeLabel('name'),
                            'aria-label' => $model->getAttributeLabel('name'),
                            'aria-describedby' => 'help-name',
                        ])->label(false) ?>

                    <?= $form->field($model, 'phone', ['errorOptions' => ['id' => 'help-phone'], 'options' => ['class' => 'form-group']])
                        ->textInput([
                            'placeholder' => $model->getAttributeLabel('phone'),
                            'aria-label' => $model->getAttributeLabel('phone'),
                            'aria-describedby' => 'help-phone',
                        ])->label(false) ?>

                    <?= $form->field($model, 'email', ['errorOptions' => ['id' => 'help-email'], 'options' => ['class' => 'form-group']])
                        ->textInput([
                            'placeholder' => $model->getAttributeLabel('email'),
                            'aria-label' => $model->getAttributeLabel('email'),
                            'aria-describedby' => 'help-email',
                        ])->label(false) ?>

                    <?php if ($campaign->show_store): ?>
                        <?= IkeaSelectWidget::widget([
                            'model' => $model,
                            'attribute' => 'store',
                            'prompt' => $model->getAttributeLabel('jobTitle'),
                            'items' => $model->stores,
                            'inputOptions' => [
                                'class' => 'form-control selectpicker',
                                'aria-label' => $model->getAttributeLabel('jobTitle'),
                                'aria-describedby' => 'help-jobTitle',
                            ],
                        ]) ?>
                    <?php endif; ?>

                    <?= IkeaSelectWidget::widget([
                        'model' => $model,
                        'attribute' => 'jobTitle',
                        'prompt' => $model->getAttributeLabel('jobTitle'),
                        'items' => $model->jobs,
                        'inputOptions' => [
                            'class' => 'form-control selectpicker',
                            'aria-label' => $model->getAttributeLabel('jobTitle'),
                            'aria-describedby' => 'help-jobTitle',
                        ],
                    ]) ?>

                    <?php if ($campaign->show_cv === 1): ?>
                        <?= $form->field($model, 'cvfile', ['errorOptions' => ['id' => 'help-cvfile'], 'options' => ['class' => 'form-group']])
                            ->fileInput([
                                'class' => 'sr-only',
                                'aria-label' => $model->getAttributeLabel('cvfile'),
                                'aria-describedby' => 'help-cvfile',
                                'accept' => '.pdf,.rtf,.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            ])->label('<i class="glyphicon glyphicon-paperclip" style="margin-left: 10px;"></i>' . $model->getAttributeLabel('cvfile')) ?>
                    <?php endif; ?>
                    <p class="text-small agreement">
                        <small>
                            <?= $this->render('userAgreament') ?>
                        </small>
                    </p>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn bg-yellow fg-blue text-bold col-xs-12', 'name' => 'contact-button']) ?>
                    </div>
                    <?= $form->field($model, 'supplierId')->hiddenInput()->label(false) ?>
                    <?php ActiveForm::end(); ?>

                    <div class="ikea-actions row">
                        <div class="contact-text col-xs-12">
                            <br>
                            <span class="fg-white"><?= $campaign->contact ?></span>
                        </div>
                    </div>
                </div>

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

<?php endif; ?>