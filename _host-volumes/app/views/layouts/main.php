<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\AppAssetCampaign;
use yii\helpers\Url;

AppAssetCampaign::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"  dir="rtl"  prefix="og: http://ogp.me/ns#">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="<?= Yii::$app->charset ?>">

    <!-- OPEN GRAPH  -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Elbit" />
    <meta property="og:description" content="" />
    <meta property="og:locale" content="he_IL" />
    <meta property="og:image" content="<?= Url::to('@web/uploads/images/ogimage.png') ?>" />

    <!-- GOOGLE FONTS HEEBO -->
	<link href="https://fonts.googleapis.com/css?family=Heebo:100,300,400,500,700,800,900&display=swap&subset=hebrew" rel="stylesheet">

    <!-- QUILL JS -->
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    <!-- ICON -->
    <link rel="shortcut icon" href="<?= Url::to('@web/uploads/images/logo.png') ?>">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>

    <?php if (isset($this->blocks['tag_header'])): ?>
        <?= $this->blocks['tag_header'] ?>
    <?php endif; ?>
</head>
<body class="campaign">

<?php if (isset($this->blocks['tag_body'])): ?>
    <?= $this->blocks['tag_body'] ?>
<?php endif; ?>
    
<?php $this->beginBody() ?>

<div class="wrapper">
    <div class="row alerts">
        <div class="col-xs-10 col-sm-8 col-xs-offset-1 col-sm-offset-2 text-center">
            <?= Alert::widget() ?>
        </div>
    </div>

    <?= $content ?>
</div>

<?php $this->endBody() ?>

<?php
    $script = '';
    $this->registerJs($script, yii\web\View::POS_READY);
?>
</body>
</html>
<?php $this->endPage() ?>
