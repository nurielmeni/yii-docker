<?php

use app\helpers\Helper;

/**
 * Renders one job by $job details
 * 'Description', 
 * 'JobId', 
 * 'JobTitle', 
 * 'JobCode',
 * 'RegionValue', 
 * 'RegionText',
 * 'UpdateDate',
 * data-lastupdate="LastUpdate"
 * */
?>

<?php if (isset($job)) : ?>
    <tr 
        data-description="<?= Helper::getArrValue($job, 'Description') ?>" 
        data-requirements="<?= Helper::getArrValue($job, 'Requiremets') ?>"
        data-jobcode="<?= Helper::getArrValue($job, 'JobCode') ?>"
        data-cityname="<?= Helper::getArrValue($job, 'CityName') ?>"
    >
        <td>
            <div class="checkbox">
                <input type="checkbox" id="<?= Helper::getArrValue($job, 'JobId') ?>" name="<?= Helper::getArrValue($job, 'JobId') ?>" value="<?= Helper::getArrValue($job, 'JobTitle') ?>">
                <label for="<?= Helper::getArrValue($job, 'JobId') ?>"></label>
                <span class="show-job-details"><?= Helper::getArrValue($job, 'JobTitle') ?></span>
            </div>
        </td>
        <td data-title="קוד משרה: " class="show-job-details jobcode"><?= Helper::getArrValue($job, 'JobCode') ?></td>
        <td data-title="אזור גאוגרפי: " class="show-job-details region"><?= Helper::getArrValue($job, 'RegionText') ?></td>
        <td class="apply-job" data-title="מיקום/עיר">
            <span class="show-job-details cityname"><?= Helper::getArrValue($job, 'CityName') ?></span>
            <a href="#" job-id="<?= Helper::getArrValue($job, 'JobId') ?>" class="btn-table download apply">הגשת קו"ח ></a>
            <a href="#" style="display: none;" class="btn-table download close-details">סגור</a>
        </td>
    </tr>
<?php endif ?>