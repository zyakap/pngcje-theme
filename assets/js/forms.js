/**
 * assets/js/forms.js
 * PNGCJE Custom Form Builder — Frontend Submission Handler
 */
(function ($) {
    'use strict';

    $(document).on('submit', '.pngcje-form', function (e) {
        e.preventDefault();

        var $form    = $(this);
        var $wrap    = $form.closest('.pngcje-form-wrap');
        var $btn     = $form.find('[type="submit"]');
        var $label   = $btn.find('.pngcje-submit-label');
        var $spinner = $btn.find('.pngcje-submit-spinner');
        var $success = $wrap.find('.pngcje-form-success');
        var $errGlob = $wrap.find('.pngcje-form-error-global');

        // Clear previous state
        $wrap.find('.pngcje-field-error').hide().text('');
        $wrap.find('.pngcje-field-wrap').removeClass('pngcje-field--error');
        $errGlob.hide().text('');
        $success.hide().text('');

        // Client-side required check
        var clientErrors = false;
        $form.find('.pngcje-field-input[required]').each(function () {
            if (!$(this).val().trim()) {
                var $wrap_f = $(this).closest('.pngcje-field-wrap');
                $wrap_f.addClass('pngcje-field--error');
                $wrap_f.find('.pngcje-field-error').text(
                    ($(this).prev('label').text().replace(' *','').trim() || 'This field') + ' is required.'
                ).show();
                clientErrors = true;
            }
        });
        if (clientErrors) {
            $form.find('.pngcje-field--error:first .pngcje-field-input').focus();
            return;
        }

        // Submitting state
        $btn.prop('disabled', true);
        $label.text(pngcjeFormsData.strings.submitting);
        $spinner.show();

        var formData = new FormData($form[0]);

        $.ajax({
            url:         pngcjeFormsData.ajaxUrl,
            type:        'POST',
            data:        formData,
            processData: false,
            contentType: false,
            success: function (res) {
                $btn.prop('disabled', false);
                $spinner.hide();
                $label.text($form.find('[name="submit_label"]').val() || 'Submit');

                if (res.success) {
                    if (res.data && res.data.redirect) {
                        window.location.href = res.data.redirect;
                        return;
                    }
                    // Show success message
                    var msg = (res.data && res.data.message) ? res.data.message : 'Thank you!';
                    $form.fadeOut(300, function () {
                        $success
                            .empty()
                            .append($('<div>', { class: 'pngcje-form-confirmation' }).text(msg))
                            .fadeIn(300);
                        // Scroll to success
                        $('html,body').animate({ scrollTop: $success.offset().top - 100 }, 400);
                    });
                } else {
                    // Server validation errors
                    if (res.data && res.data.errors) {
                        $.each(res.data.errors, function (fieldName, errMsg) {
                            var $input = $form.find('[name="fields[' + fieldName + ']"]');
                            var $fw = $input.closest('.pngcje-field-wrap');
                            $fw.addClass('pngcje-field--error');
                            $fw.find('.pngcje-field-error').text(errMsg).show();
                        });
                        $form.find('.pngcje-field--error:first .pngcje-field-input').focus();
                    } else {
                        var errMsg = (res.data && res.data.message) ? res.data.message : pngcjeFormsData.strings.error;
                        $errGlob.text(errMsg).show();
                    }
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                $spinner.hide();
                $label.text('Submit');
                $errGlob.text(pngcjeFormsData.strings.error).show();
            }
        });
    });

    // Real-time error clearing
    $(document).on('input change', '.pngcje-field-input', function () {
        var $fw = $(this).closest('.pngcje-field-wrap');
        if ($fw.hasClass('pngcje-field--error')) {
            $fw.removeClass('pngcje-field--error');
            $fw.find('.pngcje-field-error').hide();
        }
    });

})(jQuery);
