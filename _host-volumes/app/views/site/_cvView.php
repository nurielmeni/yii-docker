<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'אלביט - הגשת מועמדות';
?>

<div dir="rtl">
    <h3>קובץ קורות חיים אוטומטי - אלביט משרות</h3>
    <br>
    <h4>פרטי מועמד</h4>
    <?php foreach ($model->attributes as $name => $value): ?>
        <?php if ($name === 'cvfile' || $name === 'supplierId' || $name === 'show_store' || $name === 'jobDetails')
            continue; ?>
        <?php if ($model->jobDetails && $name === 'jobTitle'): ?>
            <p><span style="font-weight: bold;"><?= $model->getAttributeLabel($name) ?>: </span>
                <?= $model->jobDetails->JobTitle ?></p>
        <?php else: ?>
            <p><span style="font-weight: bold;"><?= $model->getAttributeLabel($name) ?>: </span> <?= $value ?></p>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($model->jobDetails): ?>
        <p><span style="font-weight: bold;">קוד משרה: </span> <?= $model->jobDetails->JobCode ?></p>
    <?php endif; ?>
</div>