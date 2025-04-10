<?php

use app\helpers\Helper;
?>

<div id="<?= $name ?>" class="submit-popup" style="display: none;">

	<div class="modal-backdrop"></div>
	<div class="modal-popup popup-elbit">
		<div class="close-popup"><img src="images/close.png" alt="Close"></div>
		<div class="inner-popup popup-elbit">
			<h2><?= Yii::t('app', 'Submit your CV') ?></h2>
			<form action="/">
				<div class="form-field">
					<label for="name"><?= Yii::t('app', 'Full Name') ?></label>
					<input type="text" name="name" id="name" required placeholder="<?= Yii::t('app', 'Full Name') ?>">
				</div>
				<div class="form-field">
					<label for="idnumber"></label>
					<input type="text" name="idnumber" id="idnumber" placeholder="<?= Yii::t('app', 'Id Number') ?>">
				</div>
				<div class="form-field file-form">
					<label class="file" for="cvfile"><?= Yii::t('app', 'Select file') ?></label>
					<input type="file" class="inputfile" name="cvfile" id="cvfile" placeholder="" accept=".pdf, .rtf, .doc,.docx, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
					<div class="file-option"><?= Yii::t('app', 'No file selected, recomended file types: (doc / docx / pdf / rtf)') ?></div>
				</div>
				<button type="submit"><?= Yii::t('app', 'Submit CV') ?></button>
				<p><span class="error-summary" style="display: none;
				"><?= Yii::t('app', 'Form error, please validate your data*') ?></span></p>
			</form>
		</div>
	</div>


</div>
<?php

$js = <<<JS
    if (typeof submitPopup === 'undefined') return;

    submitPopup.init({
        name: '$name',
		campaignId: '$campaignId',
		applyUrl: '$applyUrl'
    });
JS;

$this->registerJs($js, yii\web\View::POS_READY);
