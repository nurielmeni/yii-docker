<?php

use yii\helpers\Html;

$js = '$("select#' . $inputOptions['id'] . '").niceSelect().trigger("nice-' . $inputOptions['id'] . '-ready");';
$view->registerJs($js, $view::POS_READY);
?>

<?= Html::beginTag('div', $wraperOptions) ?>
    <?= Html::beginTag('select', $inputOptions) ?>
        <?php foreach ($items as $key => $value) : ?>
            <?php if (is_array($value)) : // the key is the sort value ?>
                <?php foreach ($value as $sKey => $sValue) : ?>
                    <?= strlen($sValue) > 0 ? Html::tag('option', $sValue, ['value' => $sKey, 'class' => $key, 'store' => $key]) : '' ?>
                <?php endforeach; ?>
            <?php else : // Only key and value no sort option ?>
                <?= strlen($value) > 0 ? Html::tag('option', $value, ['value' => $key]) : '' ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?= Html::endTag('select') ?>

    <?= Html::tag('p', '', ['id' => 'help-' . $attribute, 'class' => 'help-block help-block-error']) ?>
<?= Html::endTag('div') ?>