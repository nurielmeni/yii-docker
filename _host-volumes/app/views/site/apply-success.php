<?php $this->beginBlock('tag_header') ?>
<?= $campaign->tag_header ?>
<?php $this->endBlock() ?>

<?php $this->beginBlock('tag_body') ?>
<?= $campaign->tag_body ?>
<?php $this->endBlock() ?>

<h1><?= Yii::t('app', 'CV Submitted Successfuly') ?></h1>
<p><?= Yii::t('app', 'The Cv were submited successfully') ?></p>