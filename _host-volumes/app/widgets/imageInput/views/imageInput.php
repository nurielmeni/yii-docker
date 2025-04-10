<?php

use yii\helpers\Html;
?>

<?= Html::beginTag('div', $htmlOptions) ?>
<?=

Html::img('@web/' . $src, [
    'id' => $id . '-preview',
    'onclick' => 'getElementById("' . $id . '").click();',
    'style' => 'width: 100%; height: 100%; max-width: 300px;',
    'class' => 'form-control',
])
?>
<?=

Html::fileInput(ucfirst($context) . '[' . $attribute . ']', null, [
    'id' => $id,
    'style' => 'display: none;',
    'class' => 'image-input',
    'accept' => 'image/*',
    'value' => $src,
])
?>
<?= Html::endTag('div') ?>