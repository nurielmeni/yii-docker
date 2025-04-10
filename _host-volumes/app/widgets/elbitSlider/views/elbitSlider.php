<?php

use app\helpers\Helper;
?>

<div id="<?= $name ?>" class="section-client">
    <div class="container">
        <div class="slider-client">
            <div class="arrows-slider">
                <span class="btn-arrows next"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                <span class="btn-arrows prev"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
            </div>

            <div class="slider-items">
            <?php foreach ($items as $key => $item) : ?>
                <div class="item-client" key="<?= $key ?>">
                    <div class="inner-item-client">
                        <div class="right-client">
                            <a href="#"><img src="<?= $item['image'] ?>" alt="Client"></a>
                        </div>
                        <div class="left-client">
                            <h3><a href=""><?= Helper::getObjValue($item, 'name') ?></a></h3>
                            <span><?= Helper::getObjValue($item, 'title') ?></span>
                            <p><?= Helper::getObjValue($item, 'body') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>

            <div class="dots-slider">
                <div class="inner-arrows-slider">
                    <?php foreach ($items as $key => $item) : ?>
                        <span class="btn-dots" key="<?= $key ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php

$js = <<<JS
    if (typeof elbitSlider === 'undefined') return;

    elbitSlider.init({
        name: '$name'
    });
JS;

$this->registerJs($js, yii\web\View::POS_READY);
