var submitPopup = (function ($) {
  var elbitPopup,
    elbitForm,
    elbitSubmit,
    errorSummary,
    elbitClose,
    campaignId,
    jobs;

  function init(options) {
    console.log('Submit Popup: Init');
    campaignId = options.campaignId;

    elbitPopup = '#' + options.name;
    elbitPopupModal = '#' + options.name + ' .modal-popup.popup-elbit';
    elbitPopupInner = '#' + options.name + ' .inner-popup.popup-elbit';
    elbitForm = '#' + options.name + ' form';
    elbitSubmit = '#' + options.name + ' button[type="button"]';
    elbitClose = '#' + options.name + ' div.close-popup';
    errorSummary = '#' + options.name + ' span.error-summary';

    applyResponse = '<div id="apply-response"><div class="loader"></div></div>';

    setValidators();

    var validator = $(elbitForm).validate({
      rules: {
        idnumber: {
          required: true,
          idnumber: true,
        },
        cvfile: {
          required: true,
          filetypes: true,
        },
      },
      invalidHandler: function () {
        $(errorSummary).show();
      },
      submitHandler: function (form, el) {
        el.stopImmediatePropagation();
        el.stopPropagation();
        el.preventDefault();

        var formData = new FormData(form);
        formData.append('campaignid', campaignId);
        formData.append('jobs', jobs);

        $.ajax({
          url: options.applyUrl,
          data: formData,
          contentType: false,
          cache: false,
          processData: false,
          dataType: 'json',
          type: 'POST',
          beforeSend: actionBeforeSend,
          success: actionSuccess.bind(this),
          error: actionError.bind(this),
        });

        console.log('submitHandler', form);
      },
    });

    function actionBeforeSend() {
      console.log('actionBeforeSend', this);
      $(elbitPopupInner).hide().after(applyResponse);
    }

    function actionSuccess(res) {
      console.log('actionSuccess', res);
      $('#apply-response .loader').remove();
      window.location.href = '/site/apply-success/' + campaignId;
      //$('#apply-response').html(res.html);
    }

    function actionError(res) {
      console.log('actionError', res);
      $('#apply-response .loader').remove();
      window.location.href = '/site/apply-success/' + campaignId;
      //$('#apply-response').html('<h1>התרחשה שגיאה</h1><p>שליחת קורות החיים נכשלה.</p><p>נסה מאוחר יותר</p>');
    }

    $(elbitSubmit).on('click', function () {
      validator.form();
    });

    $(elbitClose).on('click', function () {
      $(elbitPopupModal).fadeOut(400, function () {
        $(elbitPopup).hide();
      });
    });
  }

  function show(selectedJobs) {
    // selectedJobs: null, submit general
    if (
      selectedJobs !== null &&
      (!Array.isArray(selectedJobs) || selectedJobs.length === 0)
    )
      return;

    jobs = selectedJobs;
    $(elbitPopupModal).hide();
    $('#apply-response').remove();
    $(elbitPopupInner).show();
    $(elbitPopup).show();
    $(elbitPopupModal).fadeIn();
  }

  function setValidators() {
    $.validator.addMethod(
      'idnumber',
      function (value, element) {
        return this.optional(element) || idnumberValidate(value);
      },
      jQuery.validator.format('נא להזין מספר זהות חוקי')
    );

    $.validator.addMethod(
      'phone',
      function (value, element) {
        return this.optional(element) || phoneValidate(value);
      },
      jQuery.validator.format('נא להזין מספר טלפון חוקי')
    );

    $.validator.addMethod(
      'filetypes',
      function (value, element) {
        return this.optional(element) || filetypesValidate(value);
      },
      jQuery.validator.format('סוג קובץ לא נתמך')
    );
  }

  function idnumberValidate(value) {
    // DEFINE RETURN VALUES
    var R_ELEGAL_INPUT = false; // -1
    var R_NOT_VALID = false; // -2
    var R_VALID = true; // 1

    //INPUT VALIDATION

    // Just in case -> convert to string
    var IDnum = String(value);

    // Validate correct input (Changed from 5 to 9 so only 9 digits are allowed)
    if (IDnum.length > 9 || IDnum.length < 9) return R_ELEGAL_INPUT;
    if (isNaN(IDnum)) return R_ELEGAL_INPUT;

    // The number is too short - add leading 0000
    if (IDnum.length < 9) {
      while (IDnum.length < 9) {
        IDnum = '0' + IDnum;
      }
    }

    // CHECK THE ID NUMBER
    var mone = 0,
      incNum;
    for (var i = 0; i < 9; i++) {
      incNum = Number(IDnum.charAt(i));
      incNum *= (i % 2) + 1;
      if (incNum > 9) incNum -= 9;
      mone += incNum;
    }
    if (mone % 10 == 0) return R_VALID;
    else return R_NOT_VALID;
  }

  function filetypesValidate(value) {
    var validExt = ['doc', 'docx', 'pdf', 'rtf'];

    ext = value.split('.').pop();

    return validExt.indexOf(ext) > -1;
  }

  function phoneValidate(value) {
    var regex = /^0[0-9]{1,2}[-\s]{0,1}[0-9]{3}[-\s]{0,1}[0-9]{4}/i;
    return value && regex.test(String(value).trim().toLowerCase());
  }

  return {
    init: init,
    show: show,
  };
})(jQuery);
