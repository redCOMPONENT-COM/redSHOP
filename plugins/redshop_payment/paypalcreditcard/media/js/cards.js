jQuery(document).ready(function ($) {

    jQuery('#newCardform').hide();
    jQuery('[id^="card-edit-"]').hide();

    jQuery(document).on('click', '.edit, .cancel', function () {
        var id = jQuery(this).attr('cardId');

        if (id != 0) {
            var el = jQuery('#card-edit-' + id);

            el.find('[name="cardNumber"]').attr('readonly', 'readonly');

            el.fadeToggle('slow');
        } else {
            jQuery('#newCardform').fadeToggle('slow');
        }
    });

    jQuery(document).on('click', '[id^="save-"]', function (event) {
        event.preventDefault();

        jQuery(this).attr('disabled', 'disabled');
        jQuery('.cancel').attr('disabled', 'disabled');
        jQuery('.credit-card-form').css('opacity', '0.3');

        var id = this.id.replace('save-', '');
        var el = (id == 0) ? jQuery('#newCardform') : jQuery('#card-edit-' + id);
        var taskName = (id == 0) ? 'new' : 'update';

        var params = {
            option: 'com_redshop',
            view: 'account',
            layout: 'cards',
            plugin: 'paypalcreditcard',
            task: taskName,
            cardId: id,
            cardType: el.find('[name="cardType"]').val(),
            cardNumber: el.find('[name="cardNumber"]').val(),
            cardName: el.find('[name="cardName"]').val(),
            cardExpireMonth: el.find('[name="cardExpireMonth"]').val(),
            cardExpireYear: el.find('[name="cardExpireYear"]').val(),
            cardCvv: el.find('[name="cardCvv"]').val()
        };

        jQuery.ajax({
            url: redSHOP.RSConfig._('SITE_URL') + '?tmpl =component',
            type: 'POST',
            dataType: 'json',
            data: params,
        })
            .always(function (data, textStatus) {

                if (textStatus == 'timeout' || textStatus == 'parsererror') {
                    jQuery('.ajax-error').html('<span class="label label-important">Server Timeout</span>');
                } else if (typeof data === 'undefined' || textStatus == 'error') {
                    jQuery('.ajax-error').html('<span class="label label-important">Application Error</span>');
                } else {
                    if (data.messages.length > 0) {
                        var hasImportant = false;

                        jQuery(data.messages).each(function (messageIdx, messageData) {
                            jQuery('.ajax-error').html(messageData.message);

                            if (messageData.type_message != 'success') {
                                hasImportant = true;
                            }
                        });

                        if (!hasImportant) {
                            if ('update' == taskName) {
                                var fields = jQuery('#card-' + id).children();

                                // Find first td has radio input or not
                                var tdIndex = (jQuery(fields.get(0)).find('input').length > 0) ? 1 : 0;

                                jQuery(fields.get(tdIndex)).html(params.cardName);
                                jQuery(fields.get(tdIndex + 3)).html(params.cardExpireMonth);
                                jQuery(fields.get(tdIndex + 4)).html(params.cardExpireYear);
                            }
                            // For new task
                            else if (data.cardId != 0) {
                                jQuery('.creditCards table').prepend(data.response);
                                el.find('[name="cardNumber"]').val('');
                                el.find('[name="cardName"]').val('');
                                el.find('[name="cardExpireMonth"]').val('');
                                el.find('[name="cardExpireYear"]').val('');
                                el.find('[name="cardCvv"]').val('');
                            }

                            el.fadeToggle('slow');
                        }
                    }
                }

                jQuery('[id^="save-"]').removeAttr('disabled', 'disabled');
                jQuery('.cancel').removeAttr('disabled', 'disabled');
                jQuery('.credit-card-form').css('opacity', '');
            });
    });

    jQuery(document).on('click', '[id^="delete-"]', function (event) {
        event.preventDefault();
        var id = this.id.replace('delete-', '');

        jQuery('#card-' + id).css('opacity', '0.3');

        var params = {
            option: 'com_redshop',
            view: 'account',
            layout: 'cards',
            plugin: 'paypalcreditcard',
            task: 'delete',
            cardId: id
        };

        jQuery.ajax({
            url: redSHOP.RSConfig._('SITE_URL') + '?tmpl=component',
            type: 'POST',
            dataType: 'json',
            data: params,
        })
            .always(function (data, textStatus) {

                if (textStatus == 'timeout' || textStatus == 'parsererror') {
                    jQuery('.ajax-error').html('<span class="label label-important">Server Issue/Timeout</span>');
                } else if (typeof data === 'undefined' || textStatus == 'error') {
                    jQuery('.ajax-error').html('<span class="label label-important">Application Error</span>');
                } else {
                    if (data.messages.length > 0) {
                        var hasImportant = false;

                        jQuery(data.messages).each(function (messageIdx, messageData) {
                            jQuery('.ajax-error').html(messageData.message);

                            if (messageData.type_message != 'success') {
                                hasImportant = true;
                            }
                        });

                        if (!hasImportant) {
                            jQuery('#card-' + id).fadeOut('slow', function () {
                                jQuery(this).remove();
                            });
                            jQuery('#card-edit-' + id).fadeOut('slow', function () {
                                jQuery(this).remove();
                            });
                        }
                    }
                }
            });
    });

    jQuery(document).on('click', '#newCardBtn', function (event) {
        event.preventDefault();
        jQuery('#newCardform').fadeToggle('slow');
    });
});
