"use strict";

/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * - This class used to work with postDanmark.
 * - It's not ready with requirejs until requirejs applied in global
 * - redSHOP object must be implemented before
 * - All ajax request MUST BE returned as json
 * - redSHOP.Ajax is not implemented yet
 *
 * This class can work as standard alone ( except redSHOP object required )
 */

(function (w, $)
{
	/**
	 * We must use w.variable instead variable. Because we are under wrapped function.
	 * Use variable will be assume as private
	 */
	var redSHOP = w.redSHOP;

	if (typeof redSHOP.Shipping === 'undefined')
	{
		redSHOP.Shipping = {};
	}

	// Init postDanmark object
	var postDanmark = {
		version: '1.0.0',
		isMobile: (screen.width <= 480),
		useMap: true,
		ajaxIndex: 'index.php',
		country: 'DK',
		servicePoints: {},
		// Default zip code
		shippingZipcode: 5000
	};

	/**
	 * Check if element is postNord radio
	 *
	 * @param el
	 * @returns {boolean}
	 */
	postDanmark.isPostDanmarkInput = function (el)
	{
		var onClickValue = $(el).get(0).getAttribute('onclick');

		return (onClickValue.length > 1 && onClickValue.match(/'postdanmark'/) != null);
	};

	/**
	 * Validate form before submitting
	 *
	 * @returns {boolean}
	 */
	postDanmark.formValidate = function ()
	{
		if (typeof $('input[name="service_point_id"]').val() != 'undefined')
		{
			if ($('input[name="service_point_id"]').val() == '')
			{
				return false;
			}

			// Standard zip code form
			if ($('#mapMobileSeachBox').length != 0)
			{
				return $('#mapMobileSeachBox').val() != '';
			}

			// Map form
			if ($('#shop_id_pacsoft').length != 0)
			{
				return $('#shop_id_pacsoft').val() != ''
			}
		}
		return true;
	};

	/**
	 * Render standard select list with searching
	 *
	 * @param el
	 */
	postDanmark.loadLocationMobile = function (el)
	{
		/**
		 *
		 */
		$("#mapMobileSeachBox").select2({
			ajax: {
				url: postDanmark.ajaxIndex + '?option=com_redshop&view=checkout&task=getShippingInformation&tmpl=component&plugin=PostDanmark',
				dataType: 'json',
				delay: 250,
				/**
				 *
				 * @param term
				 * @param page
				 * @returns {{zipcode: *, countryCode: string}}
				 */
				data: function (term, page)
				{
					return {
						zipcode: term,
						countryCode: postDanmark.country
					};
				},
				/**
				 *
				 * @param data
				 * @param page
				 * @returns {{results: Array}}
				 */
				results: function (data, page)
				{
					var results = [];
					$.each(data.addresses, function (index, address)
					{
						var markup = '<div class="row-fluid">' +
							'<div class="span10">';
						markup += '<div>' + data.name[index] + '</div>';
						markup += '<div>' + data.city[index] + ' ' + data.postalCode[index] + '</div>';
						markup += '<div>' + address + '</div>';
						markup += '</div></div>';
						var options = {
							'id': data.servicePointId[index] + '|' + data.name[index] + '|' + address + '|' + data.postalCode[index] + '|' + data.city[index],
							'text': markup,
							'name': data.name[index],
							'poingId': data.servicePointId[index],
							'addresses': address,
							'postalCode': data.postalCode[index],
							'city': data.city[index]
						};

						results.push(options);
					})

					return {results: results};
				},
				cache: true
			},
			escapeMarkup: function (markup)
			{
				return markup;
			},
			containerCssClass: "span4",
			minimumInputLength: 4
		});
	};

	/**
	 * Show google map form to choose pickup location
	 */
	postDanmark.showForm = function ()
	{
		// Show modal but without map until we have right zip code
		jQuery.magnificPopup.open({
			items: {
				src: $('#showMap')
			},
			type: 'inline',
			enableEscapeKey: false,
			modal: true,
			showCloseBtn: false,
			callbacks: {
				open: function ()
				{
					// Do render nothing yet until we have zip code
				}
			}
		}, 0);

		postDanmark['magnificPopup'] = jQuery.magnificPopup.instance;

		// Try to get shipping zip code
		postDanmark.ajaxGetShippingZipcode(function ()
		{
			// Call back to get shipping information
			postDanmark.ajaxGetShippingInformation(postDanmark.shippingZipcode, function ()
			{
				// Call back to render map
				if (postDanmark.useMap)
				{
					postDanmark.drawMap();
				}
			});
		})

	}

	/**
	 * @TODO HTML Layout should get from server side
	 *
	 * @returns {string}
	 */
	postDanmark.getMapHtml = function ()
	{
		var mapHtml = '<meta name="viewport" content="initial-scale=1.0, user-scalable=no">';

		mapHtml += '<div id="showMap" class="white-popup mfp-hide">';
		mapHtml += '    <span id="mapMessage"></span>';
		mapHtml += '    <input type="text" id="mapSeachBox" maxlength="4" placeholder="' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_POSTAL_CODE') + '" />';
		mapHtml += '    <img src="' + redSHOP.RSConfig._('SITE_URL') + 'plugins/redshop_shipping/postdanmark/includes/images/postdanmark-logo.png" id="pd-logo"/>';
		mapHtml += '    <div id="map_canvas" style="height: 350px; width: 780px; position: relative; margin-top: 20px;"></div>';
		mapHtml += '    <div id="pickupLocations" class="pickupLocation-container">';
		mapHtml += '        <div class="map_buttons">';
		mapHtml += '        <div class="map-button-save">';
		mapHtml += '            <span>';
		mapHtml += '                <span>' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_OK') + '</span>';
		mapHtml += '            </span>';
		mapHtml += '        </div>';
		mapHtml += '        <div class="map-button-close">';
		mapHtml += '            <span>';
		mapHtml += '                <span>' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_CANCEL') + '</span>';
		mapHtml += '            </span>';
		mapHtml += '        </div>';
		mapHtml += '    </div>';
		mapHtml += '        <div id="postdanmark_list"></div>';
		mapHtml += '        <div class="clear"></div>';
		mapHtml += '    <div class="map_buttons">';
		mapHtml += '        <div class="map-button-save" style="margin-left: 10px;">';
		mapHtml += '            <span>';
		mapHtml += '                <span>' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_OK') + '</span>';
		mapHtml += '            </span>';
		mapHtml += '        </div>';
		mapHtml += '        <div class="map-button-close">';
		mapHtml += '            <span>';
		mapHtml += '                <span>' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_CANCEL') + '</span>';
		mapHtml += '            </span>';
		mapHtml += '        </div>';
		mapHtml += '    </div>';
		mapHtml += '    </div>';
		mapHtml += '</div>';

		return mapHtml;
	}

	postDanmark.drawMap = function ()
	{
		if (typeof postDanmark.servicePoints === 'object')
		{
			if (!typeof postDanmark.servicePoints.addresses === 'undefined')
			{
				// map_function.js
				initMap(
					postDanmark.servicePoints.addresses,
					postDanmark.servicePoints.name,
					postDanmark.servicePoints.number,
					postDanmark.servicePoints.opening,
					postDanmark.servicePoints.close,
					postDanmark.servicePoints.opening_sat,
					postDanmark.servicePoints.close_sat,
					postDanmark.servicePoints.lat,
					postDanmark.servicePoints.lng,
					postDanmark.servicePoints.servicePointId
				);
				$('#postdanmark_list').html(postDanmark.servicePoints.radio_html);
			}
		}
	}

	/**
	 * Main function to inject postDanmark button
	 *
	 * @param el
	 */
	postDanmark.injectButton = function (el)
	{
		// Is mobile
		if (postDanmark.useMap)
		{
			if ($('#sp_info').length == 0)
			{
				$(el).parent().after(
					'<div id="postdanmark_html_inject">' +
					'<input type="button" class="btn btn-small" onclick="redSHOP.Shipping.postDanmark.showForm()" value="' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_CHOOSE_DELIVERY_POINT') + '"  alt="#TB_inline?width=790&amp;inlineId=showMap" id="showMap_input" />' +
					'<input type="hidden" name="shop_id" id="shop_id_pacsoft" value="" />' +
					'<div id="sp_info">' +
					'<span id="sp_name"></span>' +
					'<span id="sp_address"></span>' +
					'</div>' +
					'<div id="sp_inputs">' +
					'<input type="hidden" name="service_point_id" value="" />' +
					'<input type="hidden" name="service_point_id_name" value="" />' +
					'<input type="hidden" name="service_point_id_address" value="" />' +
					'<input type="hidden" name="service_point_id_city" value="" />' +
					'<input type="hidden" name="service_point_id_postcode" value="" />' +
					'</div>' + postDanmark.getMapHtml() +
					'</div>'
				);
			}
		}
		else
		{
			if ($('#postdanmark_html_inject').length == 0)
			{
				var mobileHtml = '<input name="shop_id" id="mapMobileSeachBox" type="hidden" placeholder="' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_POSTAL_CODE') + '" maxlength="4">';
				$(el).parent().after(
					'<div id="postdanmark_html_inject">' + mobileHtml + '</div>'
				);

				postDanmark.loadLocationMobile(el);
			}
		}
	}

	/**
	 * Get shipping zipcode from shipping address
	 */
	postDanmark.ajaxGetShippingZipcode = function (callback)
	{
		$.ajax({
			url: postDanmark.ajaxIndex,
			data: {
				option: 'com_redshop',
				view: 'account_shipto',
				task: 'addshipping',
				'return': 'com_redshop',
				tmpl: 'component',
				'for': 'true',
				infoid: $('input[name="users_info_id"]:checked').val(),
				Itemid: 1
			},
			method: 'POST'
		})
			.done(function (data, textStatus, jqXHR)
			{
				postDanmark.shippingZipcode = $('#zipcode_ST', data).val();
				if (typeof callback === 'function')
				{
					callback(data, textStatus, jqXHR);
				}
			})
	}

	/**
	 *
	 * @param postcode
	 */
	postDanmark.ajaxGetShippingInformation = function (postcode, callback)
	{
		$.ajax({
			url: postDanmark.ajaxIndex,
			data: {
				option: 'com_redshop',
				view: 'checkout',
				task: 'getShippingInformation',
				tmpl: 'component',
				plugin: 'PostDanmark',
				zipcode: parseInt(postcode),
				countryCode: postDanmark.country
			},
			method: 'GET',
			dataType: 'json'
		})
			.done(function (data, textStatus, jqXHR)
			{
				postDanmark.servicePoints = data;

				if (typeof callback === 'function')
				{
					callback(data, textStatus, jqXHR);
				}
			})
			.fail(function ()
			{

			})
	}

	/**
	 * Class init
	 */
	postDanmark.init = function ()
	{

		if ($('input[value="postdanmark_postdanmark"]').attr('checked') === 'checked' || $('input[value="postdanmark_postdanmark"]').attr('type') == 'hidden')
		{
			postDanmark.injectButton($('input[value="postdanmark_postdanmark"]').parent().parent());
		}

		$('input[type="radio"][id^="shipping_rate_id"]').click(function ()
		{
			if (postDanmark.isPostDanmarkInput($(this)))
			{
				postDanmark.injectButton($(this));
			} else
			{
				$('#showMap_input, #sp_info, #sp_inputs, #showMap, #postdanmark_html_inject').remove();
			}
		});

		var body = $('body');

		body.on('click', '.moduleRowSelected', function (e)
		{
			if ($('input[value="postdanmark_postdanmark"]', $(this)).length > 0)
			{
				if ($('#showMap_input').length === 0)
				{
					postDanmark.injectButton($(this));
				}
			} else
			{
				$('#showMap_input, #sp_info, #showMap, #sp_inputs, #thickbox-css, .pn_error').remove();
			}
		});

		postDanmark.hooks();
	}

	/**
	 * General hooks
	 */
	postDanmark.hooks = function ()
	{
		// Hook clicking on shipping radio button
		$('input[type="radio"]').on('click', 'input', function (e)
		{
			if (postDanmark.isPostDanmarkInput(this) && $(this).attr('checked') && $('#showMap_input').length === 0)
			{
				postDanmark.injectButton(this);
			}
		});

		if (postDanmark.useMap)
		{
			// Map pick up location
			$('body').on('click', '.map-button-save', function ()
			{
				$('.pn_error').remove();

				if (!$('input[name="postdanmark_pickupLocation"]').is(':checked'))
				{
					if ($('#error_checked_radio').length === 0)
					{
						$('.map_buttons')
							.before('<span id="error_checked_radio" style="color: red; position: absolute; left: 200px;">' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_SELECT_ONE_OPTION') + '</span>');
					}
				} else
				{
					if ($('#error_checked_radio').length > 0)
					{
						$('#error_checked_radio').remove();
					}

					var id_element = $('input[name="postdanmark_pickupLocation"]:checked').val();
					var parent = $('input[id="' + id_element + '"]').parent().parent();
					var name = $('.point_info > strong', parent).html();

					var service_point_id = $('input[id="' + id_element + '"]').val(),
						service_point_id_name = $('.point_info > strong', parent).html(),
						service_point_id_address = $('.postdanmark_address > .street', parent).html(),
						service_point_id_city = $('.postdanmark_address > .city', parent).html(),
						service_point_id_postcode = $('.postdanmark_address > .service_postcode', parent).val();

					$('input[name=\'service_point_id\']').val(service_point_id);

					$('input[name=\'service_point_id_name\']').val(service_point_id_name);

					$('input[name=\'service_point_id_address\']').val(service_point_id_address);

					$('input[name=\'service_point_id_city\']').val(service_point_id_city);

					$('input[name=\'service_point_id_postcode\']').val(service_point_id_postcode);

					$('#sp_info #sp_name').html(name + ', ');

					$('#sp_info #sp_address').html(
						service_point_id_address + ' ' + service_point_id_city + ' ' + service_point_id_postcode
					);

					$('#shop_id_pacsoft').val(
						service_point_id + '|' + service_point_id_name + '|' + service_point_id_address + '|' + service_point_id_postcode + '|' + service_point_id_city
					);

					$.magnificPopup.close();
				}
			});

			// Close map
			$('body').on('click', '.map-button-close', function ()
			{
				jQuery.magnificPopup.close();
			});

			/**
			 * Hook on search zip code on map form
			 */
			$('body').on('keyup', '#mapSeachBox', function ()
			{
				if ($(this).val().length === 4)
				{
					var postcode = $(this).val();
					$(this).attr('placeholder', 'Søger, Vent venligst...');
					$(this).val('').attr('disabled', 'disabled');

					postDanmark.ajaxGetShippingInformation(postcode, function ()
					{
						$('#mapSeachBox').attr('placeholder', Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_ENTER_POSTAL_CODE')).removeAttr('disabled');
						postDanmark.drawMap();
					})
				}
			});
		}

		// Validate on submit
		if (postDanmark.isPostDanmarkInput(jQuery('input[name="shipping_rate_id"]:checked')))
		{
			$('input[name="checkoutnext"], input[name="checkout_final"]').on('click', 'input', function (e)
			{
				$('.pn_error').remove();
				if (postDanmark.formValidate())
				{
					$('form#adminForm').submit();
				} else
				{
					$('#sp_info').after('<div class="pn_error" style="color: red; font-weight: normal; ">' + Joomla.JText._('PLG_REDSHOP_SHIPPING_POSTDANMARK_PRESS_POINT_TO_DELIVERY') + '</div>')
					e.preventDefault();
				}
			});
		}
	}

	redSHOP.Shipping.postDanmark = postDanmark;

	$(document).ready(function ()
	{
		postDanmark.init();
	})

})(window, jQuery);