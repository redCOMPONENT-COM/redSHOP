// Only define the redSHOP namespace if not defined.
redSHOP = window.redSHOP || {};
redSHOP.postDanmark = {};
redSHOP.postDanmark.isMobile = (screen.width <= 480);

var google, service_points;

jQuery(document).ready(function () {
    redSHOP.postDanmark.useMap = (Boolean(parseInt(redSHOP.RSConfig._('USEMAP'))) && !redSHOP.postDanmark.isMobile);

    var postdanmark_input = jQuery('input[value="postdanmark_postdanmark"]');
    if (postdanmark_input.attr('checked') === 'checked' || postdanmark_input.attr('type') == 'hidden') {
        inject_button(jQuery('input[value="postdanmark_postdanmark"]').parent().parent());
    }

    jQuery('input[type="radio"]').each(function (i, item) {
        if (checkPDinput(jQuery(item)) && jQuery(item).attr('checked') && jQuery('#showMap_input').length === 0) {
            inject_button(jQuery(item));
        }
    });

    jQuery('input[type="radio"][id^="shipping_rate_id"]').click(function () {
        if (checkPDinput(jQuery(this))) {
            inject_button(jQuery(this));
        } else {
            jQuery('#showMap_input, #sp_info, #sp_inputs, #showMap, #postdanmark_html_inject').remove();
        }
    });

    var body = jQuery('body');

    body.on('click', '.moduleRowSelected', function (e) {
        if (jQuery('input[value="postdanmark_postdanmark"]', jQuery(this)).length > 0) {
            if (jQuery('#showMap_input').length === 0) {
                inject_button(jQuery(this));
            }
        } else {
            jQuery('#showMap_input, #sp_info, #showMap, #sp_inputs, #thickbox-css, .pn_error').remove();
        }
    });

    jQuery('input[name="checkoutnext"], input[name="checkout_final"]').click(function (e) {
        jQuery('.pn_error').remove();
        if (validate_postdanmark()) {
            jQuery('form#adminForm').submit();
        } else {
            jQuery('#sp_info').after('<div class="pn_error" style="color: red; font-weight: normal; ">' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_PRESS_POINT_TO_DELIVERY') + '</div>')
            e.preventDefault();
        }
    });

    body.on('click', '.map-button-save', function () {

        jQuery('.pn_error').remove();

        if (!jQuery('input[name="postdanmark_pickupLocation"]').is(':checked')) {
            if (jQuery('#error_checked_radio').length === 0) {
                jQuery('.map_buttons')
                    .before('<span id="error_checked_radio" style="color: red; position: absolute; left: 200px;">' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_SELECT_ONE_OPTION') + '</span>');
            }
        } else {
            if (jQuery('#error_checked_radio').length > 0) {
                jQuery('#error_checked_radio').remove();
            }

            var id_element = jQuery('input[name="postdanmark_pickupLocation"]:checked').val();
            var parent = jQuery('input[id="' + id_element + '"]').parent().parent();
            var name = jQuery('.point_info > strong', parent).html();

            var service_point_id = jQuery('input[id="' + id_element + '"]').val(),
                service_point_id_name = jQuery('.point_info > strong', parent).html(),
                service_point_id_address = jQuery('.postdanmark_address > .street', parent).html(),
                service_point_id_city = jQuery('.postdanmark_address > .city', parent).html(),
                service_point_id_postcode = jQuery('.postdanmark_address > .service_postcode', parent).val();

            jQuery('input[name=\'service_point_id\']').val(service_point_id);

            jQuery('input[name=\'service_point_id_name\']').val(service_point_id_name);

            jQuery('input[name=\'service_point_id_address\']').val(service_point_id_address);

            jQuery('input[name=\'service_point_id_city\']').val(service_point_id_city);

            jQuery('input[name=\'service_point_id_postcode\']').val(service_point_id_postcode);

            jQuery('#sp_info #sp_name').html(name + ', ');

            jQuery('#sp_info #sp_address').html(
                service_point_id_address + ' ' + service_point_id_city + ' ' + service_point_id_postcode
            );

            jQuery('#shop_id_pacsoft').val(
                service_point_id + '|' + service_point_id_name + '|' + service_point_id_address + '|' + service_point_id_postcode + '|' + service_point_id_city
            );

            jQuery.magnificPopup.close();
        }
    });

    body.on('keyup', '#mapSeachBox', function () {
        if (jQuery(this).val().length === 4) {
            var map = false;
            var postcode = jQuery(this).val();
            jQuery(this).attr('placeholder', 'Søger, Vent venligst...');
            jQuery(this).val('').attr('disabled', 'disabled');

            jQuery.post(
                redSHOP.RSConfig._('SITE_URL') + 'index.php?option=com_redshop&view=checkout&task=getShippingInformation&tmpl=component&plugin=PostDanmark', {
                    'zipcode': postcode,
                    'countryCode': 'DK'
                },
                function (response) {
                    jQuery('#mapSeachBox').attr('placeholder', Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_POSTAL_CODE')).removeAttr('disabled');
                    if (response.length > 0) {
                        service_points = jQuery.parseJSON(response);
                        if (startpoint) {
                            calculateDistances();
                        }
                        if (typeof service_points === 'object') {
                            refreshMap(service_points);
                        } else {
                            jQuery('#sog_loader').replaceWith('<div style="color: red; font-weight: normal;">' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_VALID_ZIP') + '</div>');
                        }
                    } else {
                        jQuery('#sog_loader').replaceWith('<div style="color: red; font-weight: normal; ">' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_VALID_ZIP') + '</div>');
                    }
                }
            );
        }
    });

    body.on('click', '.map-button-close', function () {
        jQuery.magnificPopup.close();
    });
});


function inject_button(el) {

    // Is mobile
    if (redSHOP.postDanmark.useMap) {
        if (0 == jQuery('#sp_info').length) {
            map_contents = get_map_contents();

            jQuery(el).parent().after(
                '<div id="postdanmark_html_inject">' +
                '<input type="button" class="btn btn-small" onclick="showForm(\'showMap\')" value="' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_CHOOSE_DELIVERY_POINT') + '"  alt="#TB_inline?width=790&amp;inlineId=showMap" id="showMap_input" />' +
                '<input type="hidden" name="shop_id[pacsoft]" id="shop_id_pacsoft" value="" />' +
                '<div id="sp_info">' + '<span id="sp_name"></span>' + '<span id="sp_address"></span>' + '</div>' +
                '<div id="sp_inputs">' + '<input type="hidden" name="service_point_id" value="" />' + '<input type="hidden" name="service_point_id_name" value="" />' + '<input type="hidden" name="service_point_id_address" value="" />' + '<input type="hidden" name="service_point_id_city" value="" />' + '<input type="hidden" name="service_point_id_postcode" value="" />' + '</div>' +
                map_contents +
                '</div>'
            );

            getShippingZipcodeAjax();
        }
    }
    else {
        if (0 == jQuery('#postdanmark_html_inject').length) {
            var mobileHtml = '<input name="shop_id[pacsoft]" id="mapMobileSeachBox" type="hidden" placeholder="' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_POSTAL_CODE') + '" maxlength="4">';
            jQuery(el).parent().after(
                '<div id="postdanmark_html_inject">' + mobileHtml + '</div>'
            );

            redSHOP.postDanmark.loadLocationMobile(el);
        }
    }
}

redSHOP.postDanmark.loadLocationMobile = function (el) {
    jQuery("#mapMobileSeachBox").select2({
        ajax: {
            url: redSHOP.RSConfig._('SITE_URL') + 'index.php?option=com_redshop&view=checkout&task=getShippingInformation&tmpl=component&plugin=PostDanmark',
            dataType: 'json',
            delay: 250,
            data: function (term, page) {
                return {
                    zipcode: term,
                    countryCode: 'DK'
                };
            },
            results: function (data, page) {
                var results = [];

                for (i = 0; i < data.addresses.length; i++) {
                    var markup = '<div class="row-fluid">' +
                        '<div class="span10">';
                    markup += '<div>' + data.name[i] + '</div>';
                    markup += '<div>' + data.city[i] + ' ' + data.postalCode[i] + '</div>';
                    markup += '<div>' + data.addresses[i] + '</div>';
                    markup += '</div></div>';
                    var options = {
                        'id': data.servicePointId[i] + '|' + data.name[i] + '|' + data.addresses[i] + '|' + data.postalCode[i] + '|' + data.city[i],
                        'text': markup,
                        'name': data.name[i],
                        'poingId': data.servicePointId[i],
                        'addresses': data.addresses[i],
                        'postalCode': data.postalCode[i],
                        'city': data.city[i]
                    };

                    results.push(options);
                }

                return {results: results};
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        containerCssClass: "span4",
        minimumInputLength: 4
    });
};

function refreshMap(service_points) {
    if (service_points.name.length > 0) {
        initMap(service_points.addresses, service_points.name, service_points.number, service_points.opening, service_points.close, service_points.opening_sat, service_points.close_sat, service_points.lat, service_points.lng, service_points.servicePointId);
        jQuery('#postdanmark_list').html(service_points.radio_html);
    }
}

function getShippingZipcodeAjax() {
    jQuery.post(
        redSHOP.RSConfig._('SITE_URL') + 'index.php?option=com_redshop&view=account_shipto&task=addshipping&return=checkout&tmpl=component&for=true&infoid=' + jQuery('input[name="users_info_id"]:checked').val() + '&Itemid=1',
        function (response) {
            var shipping_postcode = jQuery('#zipcode_ST', response).val();
            getZipcodeAjax(shipping_postcode);
        });
}

function getZipcodeAjax(postcode) {
    jQuery.post(
        redSHOP.RSConfig._('SITE_URL') + 'index.php?option=com_redshop&view=checkout&task=getShippingInformation&tmpl=component&plugin=PostDanmark',
        {
            zipcode: postcode,
            countryCode: 'DK'
        },
        function (response) {
            if (response.length > 0) {
                service_points = jQuery.parseJSON(response);

                if (startpoint) {
                    calculateDistances();
                }

                if (typeof service_points === 'object') {
                    refreshMap(service_points);
                } else {
                    jQuery('#sog_loader').replaceWith('<div style="color: red; font-weight: normal;">' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_VALUD_ZIP_CODE') + '</div>');
                }
            } else {
                jQuery('#sog_loader').replaceWith('<div style="color: red; font-weight: normal; ">' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_VALUD_ZIP_CODE') + '</div>');
            }
        }
    );
}

function get_map_contents() {
    var map_contents = '<meta name="viewport" content="initial-scale=1.0, user-scalable=no">';

    map_contents += '<div id="showMap" class="white-popup mfp-hide">';
    map_contents += '    <span id="mapMessage"></span>';
    map_contents += '    <input type="text" id="mapSeachBox" maxlength="4" placeholder="' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_POSTAL_CODE') + '" />';
    map_contents += '    <img src="' + redSHOP.RSConfig._('SITE_URL') + 'plugins/redshop_shipping/postdanmark/includes/images/postdanmark-logo.png" id="pd-logo"/>';
    map_contents += '    <div id="map_canvas" style="height: 350px; width: 780px; position: relative; margin-top: 20px;"></div>';
    map_contents += '    <div id="pickupLocations" class="pickupLocation-container">';
    map_contents += '        <div class="map_buttons">';
    map_contents += '        <div class="map-button-save">';
    map_contents += '            <span>';
    map_contents += '                <span>' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_OK') + '</span>';
    map_contents += '            </span>';
    map_contents += '        </div>';
    map_contents += '        <div class="map-button-close">';
    map_contents += '            <span>';
    map_contents += '                <span>' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_CANCEL') + '</span>';
    map_contents += '            </span>';
    map_contents += '        </div>';
    map_contents += '    </div>';
    map_contents += '        <div id="postdanmark_list"></div>';
    map_contents += '        <div class="clear"></div>';
    map_contents += '    <div class="map_buttons">';
    map_contents += '        <div class="map-button-save" style="margin-left: 10px;">';
    map_contents += '            <span>';
    map_contents += '                <span>' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_OK') + '</span>';
    map_contents += '            </span>';
    map_contents += '        </div>';
    map_contents += '        <div class="map-button-close">';
    map_contents += '            <span>';
    map_contents += '                <span>' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_CANCEL') + '</span>';
    map_contents += '            </span>';
    map_contents += '        </div>';
    map_contents += '    </div>';
    map_contents += '    </div>';
    map_contents += '</div>';

    return map_contents;
}

function validate_postdanmark() {
    if (typeof jQuery('input[name="service_point_id"]').val() != 'undefined') {
        if (jQuery('input[name="service_point_id"]').val() == '') {
            return false;
        }
    }
    return true;
}

function showForm(id) {

    if (id === 'showMap') {
        jQuery.magnificPopup.open({
            items: {
                src: jQuery('#showMap')
            },
            type: 'inline',
            enableEscapeKey: false,
            modal: true,
            showCloseBtn: false,
            callbacks: {
                open: function () {
                    initMap(service_points.addresses, service_points.name, service_points.number, service_points.opening, service_points.close, service_points.opening_sat, service_points.close_sat, service_points.lat, service_points.lng, service_points.servicePointId);
                }
            }
        }, 0);

        var magnificPopup = jQuery.magnificPopup.instance;
    }
}

function checkPDinput(el) {
    var oncl = jQuery(el).get(0).getAttribute('onclick');

    if (oncl.length > 1 && oncl.match(/'postdanmark'/) != null) {
        return true;
    }

    return false;
}
