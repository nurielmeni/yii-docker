<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap5\NavBar;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta http-equiv="content-type"
        content="text/html; charset=UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge">
    <meta charset="<?= Yii::$app->charset ?>">

    <!-- OPEN GRAPH  -->
    <meta property="og:type"
        content="website" />
    <meta property="og:title"
        content="<?= Yii::$app->params->siteName ?? 'קמפיינים' ?>" />
    <meta property="og:description"
        content="" />
    <meta property="og:locale"
        content="he_IL" />
    <meta property="og:image"
        content="<?= Url::to('@web/uploads/images/logo-v2.png') ?>" />

    <!-- GOOGLE FONTS HEEBO -->
    <link href="https://fonts.googleapis.com/css?family=Heebo:100,300,400,500,700,800,900&display=swap&subset=hebrew"
        rel="stylesheet">

    <!-- ICON -->
    <link rel="shortcut icon"
        href="<?= Url::to('@web/uploads/images/logo.png') ?>">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>

    <?php if (isset($this->blocks['tag_header'])): ?>
        <?= $this->blocks['tag_header'] ?>
    <?php endif; ?>
</head>

<body>

    <?php if (isset($this->blocks['tag_body'])): ?>
        <?= $this->blocks['tag_body'] ?>
    <?php endif; ?> <?php $this->beginBody() ?>

    <?php

    $this->registerJs("$(function () { 
        $('body').tooltip({
            selector: '[data-toggle=\"tooltip\"]',
                html:true
            });
        }); 
    ");
    ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Html::img('@web/images/logo.png', ['height' => '100%', 'alt' => 'Elbit logo', 'style' => 'margin-left: 10px;']) . Yii::$app->name,
            'brandOptions' => ['style' => 'display: inline-flex;'],
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-default navbar-fixed-top',
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Elbit Campaigns <?= date('Y') ?></p>

            <p class="pull-right">
                <a href="https://niloosoft.com/he/"
                    target="_blank"
                    rel="external"
                    style="text-decoration: none;">
                    POWERED BY NILOOSOFT HUNTER EDGE
                </a>
            </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>