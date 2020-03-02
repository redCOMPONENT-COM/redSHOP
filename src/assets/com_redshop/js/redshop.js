/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Only define the redSHOP namespace if not defined.
redSHOP = window.redSHOP || {};

/**
 * Custom behavior for JavaScript dynamic variables
 *
 * Allows you to call redSHOP.RSConfig._() to get a dynamic JavaScript string pushed in with JText::script() in Joomla.
 */
redSHOP.RSConfig = {
	configStrings: {},
	'_': function(key, def) {
		return typeof this.configStrings[key.toUpperCase()] !== 'undefined' ? this.configStrings[key.toUpperCase()] : def;
	},
	load: function(object) {
		for (var key in object) {
			this.configStrings[key.toUpperCase()] = object[key];
		}

		return this;
	}
};

/**
 * Identification flag to make sure that ajax order payament status check script
 *  is executed
 *
 * @type  {Boolean}
 */
redSHOP.AjaxOrderPaymentStatusExecuted = false;

/**
 * Update order payment status to DOM periodically if current status is unpaid.
 */
redSHOP.AjaxOrderPaymentStatusCheck = function(){

	var checkValidity = jQuery.trim(jQuery('#order_payment_status').html()) == Joomla.JText._('COM_REDSHOP_PAYMENT_STA_PAID');

	// Don't do anything if status is already paid.
	if (checkValidity)
	{
		return false;
	}

	jQuery.ajax({
		url: redSHOP.RSConfig._('SITE_URL') + 'index.php?option=com_redshop&view=order_detail&task=order_detail.AjaxOrderPaymentStatusCheck&tmpl=component',
		type: 'POST',
		dataType: 'HTML',
		data: {id: redSHOP.RSConfig._('orderId')},
	})
	.done(function(res) {

		// Update status to matched DOM. Make sure you add this ID in order receipt template.
		jQuery('#order_payment_status').html(res);

		// Make sure script is executed at least one time.
		if (redSHOP.AjaxOrderPaymentStatusExecuted)
		{
			setTimeout('redSHOP.AjaxOrderPaymentStatusCheck()', 10000);
		}

		redSHOP.AjaxOrderPaymentStatusExecuted = true;
	})
	.fail(function() {
		console.log("error");
	});
};

redSHOP.prepareStateList = function(countryListEle, stateListEle){
	var postData =  {
		view: 'search',
		task: 'getStatesAjax',
		country: countryListEle.val()
	};

	// Add token field
	postData[redSHOP.RSConfig._('AJAX_TOKEN')] = 1;

	jQuery.ajax({
		url: redSHOP.RSConfig._('AJAX_BASE_URL'),
		type: 'POST',
		dataType: 'json',
		data: postData
	})
	.done(function(data) {

		// Remove all the options
		stateListEle.empty();

		// Now let's hide state list by default
		jQuery('#div_state_txt').hide();
		stateListEle.parent().hide();
		stateListEle.hide();

		// And show it when it has actua options
		if (data.length)
		{
			jQuery('#div_state_txt').show();
			stateListEle.parent().show();

			// No needs to show original select if select2 is there.
			if (!jQuery('#s2id_' + stateListEle.attr('id')).length)
			{
				stateListEle.show();
			}
		}

		// Generate options for select lists
		jQuery.each(data, function(key,state) {
			stateListEle.append(jQuery("<option></option>")
						.attr("value", state.value).text(state.text));
		});

		stateListEle.trigger('change.select2')
	})
	.fail(function() {
		console.log("Error getting state list.");
	});
};

// Write script here to execute on page load - dom ready.
jQuery(document).ready(function($) {

	if (jQuery(location).attr('search').match(/&layout=receipt/))
	{
		if (jQuery('#order_payment_status').length > 0)
		{
			// Execure first time after 1 second.
			setTimeout('redSHOP.AjaxOrderPaymentStatusCheck()', 1000);

			// Then run second time after 10 second.
			setTimeout('redSHOP.AjaxOrderPaymentStatusCheck()', 10000);
		}
		else
		{
			console.warn('Make sure you add #order_payment_status ID in order receipt template');
		}
	}

	jQuery(document).on('change', 'select[id^="rs_country_"]', function() {
		redSHOP.prepareStateList(jQuery(this), jQuery('#' + jQuery(this).attr('stateid')));
	});

    $('body')
        .on('change', 'form[name^="update_cart"]', function() {
            updateCartAjax($, $(this));
        })
        .on('keyup keypress keydown', 'form[name^="update_cart"]', function(e) {
            if (event.which == 13)
            {
                updateCartAjax($, $(this));
                e.preventDefault();
            }
        })
        .on('click', '#plus, #minus', function() {
            var form = $($(this).closest('form[name^="update_cart"]'));
            updateCartAjax($, form);
        })
});

function updateCartAjax($, form)
{
    var quantity   = form.children('[name=quantity]').val();
    var productId  = form.children('[name=productId]').val();
    var cart_index = form.children('[name=cart_index]').val();
    var Itemid     = form.children('[name=Itemid]').val();
    var token      = redSHOP.RSConfig._('AJAX_TOKEN');

    var url = redSHOP.RSConfig._('SITE_URL') + 'index.php?option=com_redshop&view=cart&task=update&' + token + '=1';
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            'quantity'  : quantity,
            'productId' : productId,
            'cart_index': cart_index,
            'Itemid'    : Itemid
        },
        beforeSend: function() {
            var style = 'background: rgba(0,0,0,0.5); position: fixed; width: 100%; height: 100%; z-index: 999; display: flex; align-items: center; justify-content: center; left: 0; top: 0;';
            $('<div id="cart-ajax-loader" style="'+ style +'"><img src="/media/com_redshop/images/reloading.gif" alt="" border="0"></div>').appendTo('body');
            $('body').css({'overflow' : 'hidden'});
        },
        success: function(data) {
            $('#redshopcomponent').html($(data).find('#redshopcomponent').html());
            $(redSHOP).trigger('onAfterUpdateCartAjax', [data]);
        },
        complete: function(){
            //afer ajax call is completed
            $('#cart-ajax-loader').remove();
            $('body').css({'overflow' : ''});
        }
    });
}

function getCatalogValidation() {
    var frm = document.frmcatalog;
    var email = frm.email_address.value;
    var patt1 = new RegExp("([a-z0-9_]+)@([a-z0-9_-]+)[.][a-z]");

    if (frm.catalog_id.value == '0') {
        alert(Joomla.JText._('COM_REDSHOP_SELECT_CATALOG'));
        frm.catalog_id.focus();
        return false;
    }

    if (frm.name_2.value == '') {
        alert(Joomla.JText._('COM_REDSHOP_ENTER_NAME'));
        frm.name_2.focus();
        return false;
    }

    if (email == '') {
        alert(Joomla.JText._('COM_REDSHOP_ENTER_AN_EMAIL_ADDRESS'));
        frm.email_address.focus();
        return false;
    }
    else if (patt1.test(email) == false) {
        alert(Joomla.JText._('COM_REDSHOP_EMAIL_ADDRESS_NOT_VALID'));
        frm.email_address.focus();
        return false;
    }
    return true;
}