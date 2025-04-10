<?php $this->beginBlock('tag_header') ?>
<?= $campaign->tag_header ?>
<?php $this->endBlock() ?>

<?php $this->beginBlock('tag_body') ?>
<?= $campaign->tag_body ?>
<?php $this->endBlock() ?>

<h1><?= Yii::t('app', 'CV Submit Error') ?></h1>
<p><?= Yii::t('app', 'Something went wrong with applying for the job.') ?></p>