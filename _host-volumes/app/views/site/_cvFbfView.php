<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'אגד - הגשת מועמדות - חבר מביא חבר';
?>

<div dir="rtl">
    <h3>קובץ קורות חיים אוטומטי - אגד משרות</h3>
    <br>
    <h4>פרטי מועמד/חבר</h4>
    <?php foreach ($model->attributes as $name => $value): ?>
        <?php if ($name === 'cvfile' || $name === 'supplierId' || $name === 'myName' || $name === 'myNumber')
            continue; ?>
        <p><span style="font-weight: bold;"><?= $model->getAttributeLabel($name) ?>: </span> <?= $value ?></p>
    <?php endforeach; ?>
    <br>
    <h4>פרטי עובד ממליץ</h4>
    <p><span style="font-weight: bold;"><?= $model->getAttributeLabel('myName') ?>: </span> <?= $model->myName ?></p>
    <p><span style="font-weight: bold;"><?= $model->getAttributeLabel('myNumber') ?>: </span> <?= $model->myNumber ?>
    </p>
</div>