var nls =
  nls ||
  (function ($) {
    'use strict';
    var Validators = {
      ISRID: {
        fn: function (value) {
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
        },
        msg: 'מספר הזהות לא חוקי',
      },

      email: {
        fn: function (value) {
          var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return value && regex.test(String(value).toLowerCase());
        },
        msg: 'כתובת האימייל לא חוקית',
      },

      phone: {
        fn: function (value) {
          var regex = /^0[0-9]{1,2}[-\s]{0,1}[0-9]{3}[-\s]{0,1}[0-9]{4}/i;
          return value && regex.test(String(value).trim().toLowerCase());
        },
        msg: 'מספר הטלפון לא חוקי',
      },

      required: {
        fn: function (value) {
          return value && value.length > 0;
        },
        msg: 'שדה זה הוא שדה חובה',
      },

      fileSize: {
        fn: function (value, el, param) {
          var valid = true;
          var input = $(el).parents('div.browse').find('input[type="file"]');
          var files = input[0].files;

          for (var i = 0; i < files.length; i++) {

            // get item

            var size = files[i].size / 1024 / 1024;
            if(size > 2) valid = false;
            console.log('This file size is: ' + size + 'MiB');
        
          }


          return valid;
        },
        msg: 'גודל הקובץ המירבי הוא 2MB.',
      },

      // If no option was selected of radi will return false
      radioRequired: {
        fn: function (el) {
          var valid = false;
          var name = $(el).attr('name');
          if (typeof name === 'undefined') return valid;

          $(el)
            .parents('.nls-apply-field')
            .find('input[name="' + name + '"]')
            .each(function (i, option) {
              if ($(option).prop('checked')) valid = true;
            });
          return valid;
        },
        msg: 'יש לבחור אחת מהאפשרויות',
      },

      radioYesOnly: {
        fn: function (el) {
          var valid = false;
          var name = $(el).attr('name');
          if (typeof name === 'undefined') return valid;

          $(el)
            .parents('.nls-apply-field')
            .find('input[name="' + name + '"]')
            .each(function (i, option) {
              if ($(option).prop('checked') && $(option).val() === 'yes')
                valid = true;
            });
          this.msg = valid ? '' : $(el).attr('msg');
          return valid;
        },
      },
    };

    var validateSubmit = function (form, formData) {
      clearValidation(form);
      var valid = true;

      $(form)
        .find('input')
        .each(function (i, el) {
          if ($(el).parents('.nls-apply-field').css('display') === 'none')
            return;
          if (typeof $(el).attr('validator') === 'undefined') return;
          if (!fieldValidate(el)) valid = false;
        });
      console.log('Valid: ', valid);
      validForm();
      return valid;
    };

    var validForm = function () {
      var invalidFields = $('.nls-apply-for-jobs form .nls-invalid');
      if (invalidFields.length > 0) {
        $('.nls-apply-for-jobs .modal-footer .help-block')
          .text('אחד או יותר משדות הטופס לא תקין')
          .show();
      } else {
        $('.nls-apply-for-jobs .modal-footer .help-block').text('').hide();
      }
    };

    // Validates all of the field validators
    var fieldValidate = function (el) {
      var valid = true;
      var validatorAttr = $(el).attr('validator');
      var validators = validatorAttr.trim().split(' ');
      var type = $(el).attr('type');
      var value = type === 'radio' ? el : $(el).val();

      if (!validators.includes('required') && value.length === 0) return valid;

      validators.forEach(function (validator) {
        // If invalid skip (show only first error)
        if ($(el).hasClass('nls-invalid')) return;

        if (!Validators[validator].fn(value, el)) {
          valid = false;
          var invalidElement =
            type === 'radio' ? $(el).parents('.options-wrapper') : $(el);

          $(invalidElement).addClass('nls-invalid');
          $(el)
            .parents('.nls-apply-field')
            .find('.help-block')
            .text(Validators[validator].msg);
        }
      });

      return valid;
    };

    var clearFields = function (form) {
      form.find('input:not([type="radio"],[type="hidden"])').val('');
      clearValidation(form);
    };

    var clearValidation = function (form) {
      form.find('.nls-invalid').removeClass('nls-invalid');
      form.find('.nls-apply-field .help-block').text('');
      validForm();
    };

    var clearFieldValidation = function (el) {
      $(el)
        .parents('.nls-apply-field')
        .find('.nls-invalid')
        .removeClass('nls-invalid');
      $(el).parents('.nls-apply-field').find('.help-block').text('');
    };

    var getParam = function (param) {
      var queryString = window.location.search;
      var urlParams = new URLSearchParams(queryString);
      return urlParams.get(param);
    };

    $(document).ready(function () {
      // Handler for popup close
      $(document).on(
        'click',
        'section.popup.backdrop .card-wrapper .card-header span.close',
        function () {
          $(this).parents('section.popup.backdrop').fadeOut(600);
        }
      );

      // Show the additional document
      $('.site-navigation .add-document').on('click', function () {
        $('section.popup .card-body form').show();
        $('section.popup .card-footer').show();
        $('#apply-response').remove();
        $('section.popup.backdrop').fadeIn(600);
      });

      // Set the sid if exist
      getParam('sid') && $('input[name="sid"').val(getParam('sid'));

      // Add event listeners
      console.log('Ready Function');
      // Apply selected jobs
      $(document).on(
        'click',
        '.nls-apply-for-jobs.modal button.apply-cv',
        function (event) {
          var applyCvButton = this;
          var form = $(this).parents('.nls-apply-for-jobs').find('form');
          var formData = new FormData(form[0]);

          if (!validateSubmit(form, formData)) {
            event.preventDefault();
            return;
          }

          formData.append('action', 'apply_cv_function');

          $.ajax({
            url: apply_cv_script.applyajaxurl,
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
              $('.nls-apply-for-jobs.modal').hide();
              $('.nls-apply-for-jobs.modal').after(
                '<div id="apply-response" class="shadowed-box rounded"><div id="nls-loader" class="loader">אנא המתן...</div></div>'
              );
              var offset = $('#apply-response').offset();
              $('html, body').animate({
                scrollTop: offset.top - 100,
              });
            },
            success: function (response) {
              $('#nls-loader').remove();
              console.log('Status: ', response.status);
              $('#apply-response').html(response.html);
              // Call this function so the wp will inform the change to the post
              $(document.body).trigger('post-load');
            },
            error: function (response) {
              console.log(response);
              if (response.status === 400) {
                $('#apply-response').html(
                  '<h3>לא ניתן לשלוח את הטופס</h3><p>וודא/י שגודל הקבצים אינו עולה על 2MB ונסה/י שנית</p>'
                );
              }
            },
            type: 'POST',
          });

          event.preventDefault();
        }
      );

      // Send document
      $(document).on('click', 'button.send-document', function (event) {
        var sendDocumentButton = this;
        var form = $(this).parents('section.popup').find('form');
        var formData = new FormData(form[0]);

        if (!validateSubmit(form, formData)) {
          event.preventDefault();
          return;
        }

        formData.append('action', 'send_document_function');

        $.ajax({
          url: apply_cv_script.applyajaxurl,
          data: formData,
          contentType: false,
          cache: false,
          processData: false,
          dataType: 'json',
          beforeSend: function () {
            $('section.popup .card-body form').hide();
            $('section.popup .card-footer').hide();
            $('section.popup .card-body').append(
              '<div id="apply-response" class="shadowed-box rounded"><div id="popup-loader" class="loader">אנא המתן...</div></div>'
            );
          },
          success: function (response) {
            $('#popup-loader').remove();
            console.log('Status: ', response.sent);
            $('#apply-response').html(response.html);
            // Call this function so the wp will inform the change to the post
            $(document.body).trigger('post-load');
          },
          type: 'POST',
        });

        event.preventDefault();
      });

      // General File handler
      $('.nls-apply-field.browse button').on('click', function (e) {
        e.preventDefault();
        var fieldId = $(this).attr('field-id');
        $('input[type="file"][name^="' + fieldId + '"]').trigger('click');
      });

      $('input[type="file"]').on('change', function (e) {
        var fieldId = $(this).attr('name').split('[]')[0];
        $('.nls-apply-field.browse input[name="' + fieldId + 'Name"]')
          .val(this.files.length > 1 ? this.files.length + ' קבצים נבחרו ' : this.files[0].name)
          .addClass(this.files.length > 1 ? 'rtl' : 'ltr')
          .removeClass(this.files.length > 1 ? 'ltr' : 'rtl')
          .trigger('change');
      });

      // Clear validation errors on focus
      $('input').on('focus', function () {
        clearFieldValidation(this);
      });

      // Validate on blur and change
      $('input:not([type="radio"])').on('blur change', function () {
        if (typeof $(this).attr('validator') === 'undefined') return;
        clearFieldValidation(this);
        fieldValidate(this);
        validForm();
      });

      // Toggle visibility of radio
      $('input[type="radio"]').on('change', function () {
        var showClass = '.nls-apply-field.' + $(this).attr('name') + '-show';
        $('input[name="' + $(this).attr('name') + '"]').prop('checked')
          ? $(showClass).slideDown(300)
          : $(showClass).slideUp(300);
      });

      // Toggle visibility of select data
      $('.nls-apply-for-jobs select').on('change', function () {
        var showClass = '.nls-apply-field.' + $(this).attr('name') + '-show';
        $(this).val() !== '0'
          ? $(showClass).slideDown(300)
          : $(showClass).slideUp(300);
      });

      $('.nls-apply-for-jobs select').on('change', function () {
        var showClass =
          '.nls-apply-field.' + $(this).attr('name') + '-show-freind';
        $(this).val() === 'חבר/ה'
          ? $(showClass).slideDown(300)
          : $(showClass).slideUp(300);
      });

      // Make sure to initilize the radio display options
      $('input[type="radio"]').trigger('change');
    });

    return {
      clearFields: clearFields,
    };
  })(jQuery);

